<?php

namespace App\Services\Gdt\Report;

use App\Common\Helpers\Functions;
use App\Common\Tools\CustomException;
use App\Services\Gdt\GdtService;
use Illuminate\Support\Facades\DB;

class GdtReportService extends GdtService
{
    /**
     * @var string
     * 模型类
     */
    public $modelClass;


    public $pageSize = 200;



    /**
     * OceanAccountReportService constructor.
     * @param string $appId
     */
    public function __construct($appId = ''){
        parent::__construct($appId);
    }

    /**
     * @param array $option
     * @return bool
     * @throws CustomException
     * 同步
     */
    public function sync($option = []){
        ini_set('memory_limit', '2048M');

        $t = microtime(1);

        // 账户id过滤
        $accountIds = !empty($option['account_ids']) ? $option['account_ids'] : [];

        // 并发分片大小
        $this->setSdkMultiChunkSize($option);

        // 在跑账户
        if(!empty($option['running'])){
            $runningAccountIds = $this->getRunningAccountIds();
            $accountIds = !empty($accountIds) ? array_intersect($accountIds, $runningAccountIds) : $runningAccountIds;
        }

        $dateRange = Functions::getDateRange($option['date']);
        $dateList = Functions::getDateListByRange($dateRange);

        // 删除
        if(!empty($option['delete'])){
            $between = [
                $dateRange[0] .' 00:00:00',
                $dateRange[1] .' 23:59:59',
            ];

            $model = new $this->modelClass();

            $builder = $model->whereBetween('stat_datetime', $between);

            !empty($accountIds) && $builder->whereIn('account_id', $accountIds);

            $builder->delete();
        }

        // 账户消耗
        if(!empty($option['run_by_account_cost'])){
            $accountIds = $this->runByAccountCost($accountIds, $option['date']);
            var_dump($accountIds);
        }

        // 历史消耗
        if(!empty($option['has_history_cost'])){
            $accountIds = $this->getHasHistoryCostAccount($accountIds);
        }

        // 获取子账户组
        $accountGroup = $this->getAccountGroup($accountIds);


        foreach($dateList as $date){
            $param = [
                'date_range' => [
                    'start_date' => $date,
                    'end_date' => $date,
                ],
            ];

            $pageSize = $this->pageSize;
            foreach($accountGroup as $g){
                $items = $this->multiGetPageList($g, $pageSize, $param);

                Functions::consoleDump('count:'. count($items));

                $cost = 0;

                // 保存
                $data = [];
                foreach($items as $item) {
                    $cost += $item['cost'] ?? 0;
                    $item['date'] = $item['date'] ?? $date;
                    if(!$this->itemValid($item)){
                        continue;
                    }
                    $this->itemFormat($item);
                    $data[] = $item;
                }

                // 批量保存
                (new $this->modelClass())->chunkInsertOrUpdate($data);

                Functions::consoleDump('cost:'. $cost);
            }
        }

        $t = microtime(1) - $t;
        Functions::consoleDump($t);

        return true;
    }

    /**
     * @param $item
     * @return bool
     * 校验
     */
    protected function itemValid($item){
        $valid = true;

        if(
            empty($item['cost']) &&
            empty($item['view_count']) &&
            empty($item['valid_click_count']) &&
            empty($item['conversions_count'])
        ){
            $valid = false;
        }

        return $valid;
    }

    protected function itemFormat(&$item){
        $item['extends'] = json_encode($item);
        $item['hour'] = $item['hour'] ?? 0;
        $item['stat_datetime'] = date('Y-m-d H:i:s',strtotime(" +{$item['hour']} hours",strtotime($item['date'])));
    }


    /**
     * @param $accountIds
     * @param $date
     * @return mixed
     * 按账户消耗执行
     */
    protected function runByAccountCost($accountIds, $date){
        return $accountIds;
    }


    /**
     * @param string $date
     * @return mixed
     * @throws CustomException
     * 按日期获取账户报表
     */
    public function getAccountReportByDate($date = 'today'){
        $date = Functions::getDate($date);
        Functions::dateCheck($date);

        $model = new $this->modelClass();
        $report = $model->whereBetween('stat_datetime', ["{$date} 00:00:00", "{$date} 23:59:59"])
            ->groupBy('account_id')
            ->orderBy('cost', 'DESC')
            ->select(DB::raw("account_id, SUM(cost) cost"))
            ->get();

        return $report;
    }
}
