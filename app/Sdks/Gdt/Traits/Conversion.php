<?php

namespace App\Sdks\Gdt\Traits;

trait Conversion
{

    /**
     * @param array $accounts
     * @param int $page
     * @param int $pageSize
     * @param array $param
     * @return mixed
     * 并发获取转化列表
     */
    public function multiGetConversionList(array $accounts, $page = 1, $pageSize = 10, $param = []){
        $url = $this->getUrl('v1.3/conversions/get');
        if(!isset($param['fields'])){
            $param['fields'] =  [
                'access_status','access_type','app_android_channel_package_id','claim_type',
                'conversion_id','conversion_name','conversion_scene','create_source_type',
                'deep_behavior_optimization_goal', 'deep_worth_optimization_goal','feedback_url','is_deleted',
                'optimization_goal', 'promoted_object_id','self_attributed','site_set_enable',
                'user_action_set_id','user_action_set_key'
            ];
        }
        $param = $this->filterParam($param);

        return $this->multiGetPageList($url, $accounts, $page, $pageSize, $param);
    }
}
