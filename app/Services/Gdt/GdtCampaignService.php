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
     * @param array $param
     * @return bool
     * @throws CustomException
     * 同步
     */
    public function sync($param = []){

        $accountGroup = $this->getAccountGroup($param['account_ids']);

        $t = microtime(1);

        $pageSize = 1;
        foreach($accountGroup as $g){
            $campaigns = $this->multiGetPageList($g, $pageSize, $param);

            Functions::consoleDump('count:'. count($campaigns)."\n");

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
