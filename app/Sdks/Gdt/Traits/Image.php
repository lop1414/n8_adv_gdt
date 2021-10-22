<?php

namespace App\Sdks\Gdt\Traits;

trait Image
{

    /**
     * @param array $accounts
     * @param int $page
     * @param int $pageSize
     * @param array $param
     * @return mixed
     * 并发获取图片列表
     */
    public function multiGetImageList(array $accounts, $page = 1, $pageSize = 10, $param = []){
        $url = $this->getUrl('v1.3/images/get');
        if(!isset($param['fields'])){
            $param['fields'] =  [
                'image_id','width','height','file_size','type','signature','description','source_signature','preview_url',
                'source_type','image_usage','created_time','last_modified_time','product_catalog_id','product_outer_id',
                'source_reference_id','owner_account_id','status','sample_aspect_ratio'
            ];
        }

        return $this->multiGetPageList($url, $accounts, $page, $pageSize, $param);
    }
}
