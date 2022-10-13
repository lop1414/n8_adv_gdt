<?php

namespace App\Services\Gdt;

use App\Common\Helpers\Functions;
use App\Common\Tools\CustomException;
use App\Models\Gdt\GdtAccountImageModel;
use App\Models\Gdt\GdtImageModel;

class GdtImageService extends GdtService
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
        return $this->sdk->multiGetImageList($accounts, $page, $pageSize, $param);
    }

    /**
     * @param array $param
     * @return bool
     * @throws CustomException
     * 同步
     */
    public function sync($param = []){
        // 并发分片大小
        $this->setSdkMultiChunkSize($param);

        $accountGroup = $this->getAccountGroup($param['account_ids']);

        $t = microtime(1);

        foreach($accountGroup as $g){
            $images = $this->multiGetPageList($g, 100, $param);
            Functions::consoleDump('count:'. count($images));

            // 保存
            foreach($images as $image) {
                $this->save($image);
            }
        }

        $t = microtime(1) - $t;
        var_dump($t);

        return true;
    }



    /**
     * @param $img
     * @return bool
     * 保存
     */
    public function save($img){
        $gdtImageModel = new GdtImageModel();
        $gdtImg = $gdtImageModel->where('id', $img['image_id'])->first();

        if(empty($gdtImg)){
            $gdtImg = new GdtImageModel();
        }

        $gdtImg->id = $img['image_id'];
        $gdtImg->width = $img['width'];
        $gdtImg->height = $img['height'];
        $gdtImg->type = $img['type'];
        $gdtImg->signature = $img['signature'];
        $gdtImg->preview_url = $img['preview_url'];
        $gdtImg->source_type = $img['source_type'];
        $gdtImg->image_usage = $img['image_usage'];
        $gdtImg->owner_account_id = $img['owner_account_id'];
        $gdtImg->created_time = date('Y-m-d H:i:s',$img['created_time']);
        $ret = $gdtImg->save();

        if($ret){
            // 添加关联关系
            $this->relationAccount($img['account_id'],$img['image_id'],$img['status']);
        }

        return $ret;
    }



    /**
     * 关联账户
     * @param $accountId
     * @param $imageId
     * @param $status
     * @return GdtAccountImageModel
     */
    public function relationAccount($accountId,$imageId,$status){
        $gdtAccountImageModel = new GdtAccountImageModel();
        $gdtAccountImage = $gdtAccountImageModel->where('account_id', $accountId)
            ->where('image_id', $imageId)
            ->first();

        if(empty($gdtAccountImage)){
            $gdtAccountImage = new GdtAccountImageModel();
            $gdtAccountImage->account_id = $accountId;
            $gdtAccountImage->image_id = $imageId;
            $gdtAccountImage->status = $status;
            $gdtAccountImage->save();
        }elseif($gdtAccountImage->status != $status){

            $gdtAccountImage->status = $status;
            $gdtAccountImage->save();
        }
        return $gdtAccountImage;
    }
}
