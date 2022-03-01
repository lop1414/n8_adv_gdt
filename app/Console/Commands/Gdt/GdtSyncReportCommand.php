<?php

namespace App\Console\Commands\Gdt;

use App\Common\Console\BaseCommand;
use App\Common\Helpers\Functions;
use App\Common\Tools\CustomException;
use App\Services\Gdt\Report\GdtAccountReportService;
use App\Services\Gdt\Report\GdtAdReportService;

class GdtSyncReportCommand extends BaseCommand
{
    /**
     * 命令行执行命令
     * @var string
     */
    protected $signature = 'gdt:sync_report {--type=}  {--date=}  {--account_ids=} {--multi_chunk_size=} {--key_suffix=} {--has_history_cost=} {--run_by_account_cost=}';

    /**
     * 命令描述
     *
     * @var string
     */
    protected $description = '同步广点通报表';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct(){
        parent::__construct();
    }

    /**
     * @throws \App\Common\Tools\CustomException
     * 处理
     */
    public function handle(){

        $option = $this->option();
        $param = ['account_ids' => []];

        // 日期
        !empty($option['date']) && $param['date'] = Functions::getDate($option['date']);

        // 历史消耗
        !empty($option['has_history_cost']) && $param['has_history_cost'] = $option['has_history_cost'];

        // 账户id
        if(!empty($option['account_ids'])){
            $param['account_ids'] = explode(",", $option['account_ids']);
        }

        // 并发分片大小
        !empty($option['multi_chunk_size']) && $param['multi_chunk_size'] = min(intval($option['multi_chunk_size']), 8);

        // 锁 key
        $lockKey = 'gdt_sync_report_'.$option['type'];
        !empty($option['key_suffix']) && $lockKey .= '_'. trim($option['key_suffix']);

        $service = $this->getServices($option['type']);
        $this->lockRun([$service, 'sync'], $lockKey, 43200, ['log' => true], $param);
    }



    public function getServices($type){
        switch ($type){
            case 'account_by_day':
                echo "同步广点通账户日报表\n";
                $service = new GdtAccountReportService();
                break;
            case 'ad_by_hour':
                echo "同步广点通广告小时报表\n";
                $service = new GdtAdReportService();
                break;
            default:
                throw new CustomException([
                    'code' => 'TYPE_PARAM_INVALID',
                    'message' => 'type 无效',
                ]);
        }
        return $service;
    }
}
