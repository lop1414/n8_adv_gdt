<?php

namespace App\Sdks\Gdt\Traits;

trait Creative
{


    /**
     * @param array $accounts
     * @param int $page
     * @param int $pageSize
     * @param array $param
     * @return mixed
     * 并发获取创意列表
     */
    public function multiGetCreativeList(array $accounts, $page = 1, $pageSize = 10, $param = []){
        $url = $this->getUrl('v1.3/adcreatives/get');

        if(!isset($param['fields'])){
            $param['fields'] =  [
                'adcreative_id','campaign_id','adcreative_name',
                'adcreative_template_id','adcreative_elements','page_type','page_type','page_spec',
                'link_page_type', 'link_name_type','link_page_spec','conversion_data_type','conversion_target_type',
                'deep_link_url', 'android_deep_link_app_id','ios_deep_link_app_id','universal_link_url',
                'site_set','automatic_site_enabled','promoted_object_type','promoted_object_id',
                'profile_id','created_time','last_modified_time','share_content_spec','dynamic_adcreative_spec',
                'is_deleted','is_dynamic_creative','component_id','online_enabled','revised_adcreative_spec',
                'union_market_switch','video_end_page','feeds_video_comment_switch','webview_url','simple_canvas_sub_type',
                'floating_zone','marketing_pendant_image_id','countdown_switch','page_track_url','barrage_list',
                'app_gift_pack_code','enable_breakthrough_siteset','creative_template_version_type'
            ];
        }
        return $this->multiGetPageList($url, $accounts, $page, $pageSize, $param);
    }

}
