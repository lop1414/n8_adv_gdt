<?php

namespace App\Services\Gdt;

use App\Common\Helpers\Functions;
use App\Common\Tools\CustomException;
use App\Models\Gdt\GdtCampaignModel;

class GdtCampaignService extends GdtService
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
        return $this->sdk->multiGetCampaignList($accounts, $page, $pageSize, $param);
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

        $param = [
            'fields' => ['campaign_id','campaign_name','configured_status','campaign_type','promoted_object_type','daily_budget','total_budget','created_time','last_modified_time','speed_mode','is_deleted']
        ];
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

        $pageSize = 1;
        foreach($accountGroup as $g){
            $campaigns = $this->multiGetPageList($g, $pageSize, $param);

            Functions::consoleDump('count:'. count($campaigns));


            // 保存
            foreach($campaigns as $campaign) {
                $this->save($campaign);
            }
        }

        $t = microtime(1) - $t;
        var_dump($t);

        return true;
    }


    /**
     * @param $campaign
     * @return bool
     * 保存
     */
    public function save($campaign){
        $gdtCampaignModel = new GdtCampaignModel();
        $gdtCampaign = $gdtCampaignModel->where('id', $campaign['campaign_id'])->first();

        if(empty($gdtCampaign)){
            $gdtCampaign = new GdtCampaignModel();
        }

        $gdtCampaign->id = $campaign['campaign_id'];
        $gdtCampaign->account_id = $campaign['account_id'];
        $gdtCampaign->name = $campaign['campaign_name'];
        $gdtCampaign->configured_status = $campaign['configured_status'];
        $gdtCampaign->campaign_type = $campaign['campaign_type'];
        $gdtCampaign->promoted_object_type = $campaign['promoted_object_type'];
        $gdtCampaign->total_budget = $campaign['total_budget'];
        $gdtCampaign->daily_budget = $campaign['daily_budget'];
        $gdtCampaign->is_deleted = $campaign['is_deleted'] ? 1 : 0;
        $gdtCampaign->speed_mode = $campaign['speed_mode'];
        $gdtCampaign->create_time = date('Y-m-d H:i:s',$campaign['created_time']);
        $gdtCampaign->last_modified_time = date('Y-m-d H:i:s',$campaign['last_modified_time']);
        $ret = $gdtCampaign->save();

        return $ret;
    }
}
