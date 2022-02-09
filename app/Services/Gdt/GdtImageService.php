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
     * @param array $option
     * @return bool
     * @throws CustomException
     * 同步
     */
    public function sync($option = []){
        ini_set('memory_limit', '2048M');

        $accountIds = [];
        // 账户id过滤
        if(!empty($option['account_ids'])){
            $accountIds = $option['account_ids'];
        }

        $param = [];
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

        if(!empty($option['ids'])){
            $param['filtering'][] = [
                'field' => 'image_id',
                'operator' => 'IN',
                'values'   => $option['ids']
            ];
        }

        $accountGroup = $this->getAccountGroup($accountIds);

        $t = microtime(1);

        $pageSize = 100;
        foreach($accountGroup as $g){
            $images = $this->multiGetPageList($g, $pageSize, $param);
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
        $gdtImg->status = $img['status'];
        $gdtImg->created_time = date('Y-m-d H:i:s',$img['created_time']);
        $gdtImg->last_modified_time = date('Y-m-d H:i:s',$img['last_modified_time']);
        $ret = $gdtImg->save();

        if($ret){
            // 添加关联关系
            $this->relationAccount($img['account_id'],$img['image_id']);
        }

        return $ret;
    }


    /**
     * @param $accountId
     * @param $imageId
     * @return GdtAccountImageModel
     * 关联账户
     */
    public function relationAccount($accountId,$imageId){
        $gdtAccountImageModel = new GdtAccountImageModel();
        $gdtAccountImage = $gdtAccountImageModel->where('account_id', $accountId)
            ->where('image_id', $imageId)
            ->first();

        if(empty($gdtAccountImage)){
            $gdtAccountImage = new GdtAccountImageModel();
            $gdtAccountImage->account_id = $accountId;
            $gdtAccountImage->image_id = $imageId;
            $gdtAccountImage->save();
        }
        return $gdtAccountImage;
    }
}
