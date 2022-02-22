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
     * 并发获取图片列表
     */
    public function multiGetConversionList(array $accounts, $page = 1, $pageSize = 10, $param = []){
        $url = $this->getUrl('v1.3/conversions/get');
        if(!isset($param['fields'])){
            $param['fields'] =  [
                'conversion_id','conversion_name','access_type','claim_type','feedback_url','self_attributed',
                'optimization_goal','deep_behavior_optimization_goal','deep_worth_optimization_goal',
                'user_action_set_id','user_action_set_key', 'site_set_enable','is_deleted','access_status',
                'create_source_type','app_android_channel_package_id', 'promoted_object_id','conversion_scene'
            ];
        }

        $param = $this->filterParam($param);

        return $this->multiGetPageList($url, $accounts, $page, $pageSize, $param);
    }
}
