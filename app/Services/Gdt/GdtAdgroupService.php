<?php

namespace App\Services\Gdt;

use App\Common\Helpers\Functions;
use App\Common\Tools\CustomException;
use App\Models\Gdt\GdtAdgroupModel;

class GdtAdgroupService extends GdtService
{
    /**
     * constructor.
     * @param string $appId
     */
    public function __construct($appId = ''){
        parent::__construct($appId);
    }



    /**
     * @param $accounts
     * @param $page
     * @param $pageSize
     * @param array $param
     * @return mixed
     * sdk并发获取列表
     */
    public function sdkMultiGetList($accounts, $page, $pageSize, $param = []){
        return $this->sdk->multiGetAdgroupList($accounts, $page, $pageSize, $param);
    }

    /**
     * @param array $option
     * @return bool
     * @throws CustomException
     * 同步
     */
    public function sync($option = []){
        $accountIds = [];
        // 账户id过滤
        if(!empty($option['account_ids'])){
            $accountIds = $option['account_ids'];
        }

        $param = [];
        if(!empty($option['date'])){
            $date =  Functions::getDate($option['date']);
            $param['filtering'] = [
                [
                    'field' => 'last_modified_time',
                    'operator' => 'GREATER_EQUALS',
                    'values'   => [strtotime($date .' 00:00:00')]
                ]
            ];
        }

        $accountGroup = $this->getAccountGroup($accountIds);

        $t = microtime(1);

        $pageSize = 100;
        foreach($accountGroup as $g){
            $adgroups = $this->multiGetPageList($g, $pageSize, $param);

            Functions::consoleDump('count:'. count($adgroups));

            // 保存
            foreach($adgroups as $adgroup) {
                $this->save($adgroup);
            }
        }

        $t = microtime(1) - $t;
        var_dump($t);

        return true;
    }


    /**
     * @param $adgroup
     * @return bool
     * 保存
     */
    public function save($adgroup){
        $gdtAdgroupModel = new GdtAdgroupModel();
        $gdtAdgroup = $gdtAdgroupModel->where('id', $adgroup['adgroup_id'])->first();

        if(empty($gdtAdgroup)){
            $gdtAdgroup = new GdtAdgroupModel();
        }

        $gdtAdgroup->id = $adgroup['adgroup_id'];
        $gdtAdgroup->name = $adgroup['adgroup_name'];
        $gdtAdgroup->account_id = $adgroup['account_id'];
        $gdtAdgroup->campaign_id = $adgroup['campaign_id'];
        $gdtAdgroup->site_set = $adgroup['site_set'];
        $gdtAdgroup->optimization_goal = $adgroup['optimization_goal'];
        $gdtAdgroup->bid_mode = $adgroup['bid_mode'] ?? '';
        $gdtAdgroup->bid_amount = $adgroup['bid_amount'];
        $gdtAdgroup->daily_budget = $adgroup['daily_budget'];
        $gdtAdgroup->configured_status = $adgroup['configured_status'];
        $gdtAdgroup->bid_strategy = $adgroup['bid_strategy'];
        $gdtAdgroup->auto_audience = $adgroup['auto_audience'];
        $gdtAdgroup->conversion_id = $adgroup['conversion_id'];
        $gdtAdgroup->system_status = $adgroup['system_status'];
        $gdtAdgroup->status = $adgroup['status'];
        $gdtAdgroup->smart_bid_type = $adgroup['smart_bid_type'];
        $gdtAdgroup->is_deleted = $adgroup['is_deleted'];
        $gdtAdgroup->is_deleted = $adgroup['is_deleted'];
        $gdtAdgroup->created_time = date('Y-m-d H:i:s',$adgroup['created_time']);
        $gdtAdgroup->last_modified_time = date('Y-m-d H:i:s',$adgroup['last_modified_time']);
        $gdtAdgroup->extends = $adgroup;
        $ret = $gdtAdgroup->save();

        return $ret;
    }
}
