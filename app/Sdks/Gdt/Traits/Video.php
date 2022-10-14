<?php

namespace App\Sdks\Gdt\Traits;

trait Video
{
    /**
     * @param $accountId
     * @param $signature
     * @param $file
     * @param string $filename
     * @return mixed
     * 上传
     */
    public function addVideo($accountId, $signature, $file, $filename = ''){
        $url = $this->getUrl('v1.3/videos/add');
        $param = [
            'account_id' => $accountId,
            'signature'  => $signature,
            'video_file' => $file,
        ];

        !empty($filename) && $param['description'] = $filename;

        return $this->fileRequest($url, $param);
    }

    /**
     * @param array $accounts
     * @param int $page
     * @param int $pageSize
     * @param array $param
     * @return mixed
     * 并发获取图片列表
     */
    public function multiGetVideoList(array $accounts, $page = 1, $pageSize = 10, $param = []){
        $url = $this->getUrl('v1.3/videos/get');
        if(!isset($param['fields'])){
            $param['fields'] =  [
                'video_id','width','height','video_frames','video_fps',
                'video_codec','video_bit_rate','audio_codec','audio_bit_rate',
                'file_size','type', 'signature','system_status','description',
                'preview_url','key_frame_image_url','created_time','last_modified_time',
                'video_profile_name','audio_sample_rate','max_keyframe_interval','min_keyframe_interval','sample_aspect_ratio',
                'audio_profile_name','scan_type','image_duration_millisecond','audio_duration_millisecond',
                'source_type','product_catalog_id','product_outer_id','source_reference_id','owner_account_id','status'
            ];
        }

        if(!empty($param['ids'])){
            $param['filtering'][] = [
                [
                    'field' => 'video_id',
                    'operator' => 'IN',
                    'values'   => $param['ids']
                ]
            ];
            unset($param['ids']);
        }

        $param = $this->filterParam($param);

        return $this->multiGetPageList($url, $accounts, $page, $pageSize, $param);
    }
}
