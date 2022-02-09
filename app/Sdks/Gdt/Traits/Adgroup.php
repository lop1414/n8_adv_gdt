<?php

namespace App\Sdks\Gdt\Traits;

trait Adgroup
{


    /**
     * @param array $accounts
     * @param int $page
     * @param int $pageSize
     * @param array $param
     * @return mixed
     * 并发获取广告组列表
     */
    public function multiGetAdgroupList(array $accounts, $page = 1, $pageSize = 10, $param = []){
        $url = $this->getUrl('v1.3/adgroups/get');
        if(!isset($param['fields'])){
            $param['fields'] =  [
                    'campaign_id','adgroup_id','adgroup_name','site_set','automatic_site_enabled','optimization_goal','bid_mode','bid_amount',
                    'daily_budget','promoted_object_type','configured_status','created_time','last_modified_time','is_deleted',
                    'promoted_object_id','begin_date','end_date','time_series','user_action_sets','dynamic_creative_id','cost_guarantee_message',
                    'cost_guarantee_status','bid_strategy','auto_audience','expand_enabled','conversion_id','deep_conversion_behavior_bid',
                    'system_status','status','bid_mode','bid_adjustment','auto_acquisition_enabled','auto_acquisition_budget','creative_display_type',
                    'smart_bid_type','smart_cost_cap','marketing_scene','custom_adgroup_tag'
            ];
        }
        return $this->multiGetPageList($url, $accounts, $page, $pageSize, $param);
    }




}
