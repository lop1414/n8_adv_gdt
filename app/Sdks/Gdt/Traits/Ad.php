<?php

namespace App\Sdks\Gdt\Traits;

trait Ad
{


    /**
     * @param array $accounts
     * @param int $page
     * @param int $pageSize
     * @param array $param
     * @return mixed
     * 并发获取创意列表
     */
    public function multiGetAdList(array $accounts, $page = 1, $pageSize = 10, $param = []){
        $url = $this->getUrl('v1.3/ads/get');

        if(!isset($param['fields'])){
            $param['fields'] =  [
                'ad_id','adcreative_id','campaign_id','adgroup_id','ad_name',
                'configured_status','system_status','audit_spec','impression_tracking_url',
                'click_tracking_url', 'feeds_interaction_enabled','reject_message','is_deleted',
                'is_dynamic_creative', 'created_time', 'last_modified_time',

            ];
        }
        return $this->multiGetPageList($url, $accounts, $page, $pageSize, $param);
    }

}
