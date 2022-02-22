<?php

namespace App\Services\Gdt;

use App\Common\Helpers\Functions;
use App\Common\Tools\CustomException;
use App\Models\Gdt\GdtAccountImageModel;
use App\Models\Gdt\GdtConversionModel;

class GdtConversionService extends GdtService
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
        return $this->sdk->multiGetConversionList($accounts, $page, $pageSize, $param);
    }

    /**
     * @param array $param
     * @return bool
     * @throws CustomException
     * 同步
     */
    public function sync($param = []){
        ini_set('memory_limit', '2048M');

        $accountGroup = $this->getAccountGroup($param['account_ids']);

        $t = microtime(1);

        $pageSize = 10;
        foreach($accountGroup as $g){
            $conversions = $this->multiGetPageList($g, $pageSize, $param);
            dd($conversions);
            Functions::consoleDump('count:'. count($conversions));

            // 保存
            foreach($conversions as $conversion) {
                $this->save($conversion);
            }
        }

        $t = microtime(1) - $t;
        var_dump($t);

        return true;
    }

    /**
     * @param $conversion
     * @return bool
     * 保存
     */
    public function save($conversion){
        $gdtConversionModel = new GdtConversionModel();
        $gdtConversion = $gdtConversionModel->where('id', $conversion['conversion_id'])->first();

        if(empty($gdtConversion)){
            $gdtConversion = new GdtConversionModel();
        }

        $gdtConversion->id = $conversion['conversion_id'];
        $gdtConversion->name = $conversion['conversion_name'];
        $gdtConversion->account_id = $conversion['account_id'];
        $gdtConversion->access_type = $conversion['access_type'];
        $gdtConversion->claim_type = $conversion['claim_type'];
        $gdtConversion->feedback_url = $conversion['feedback_url'];
        $gdtConversion->self_attributed = $conversion['self_attributed'];
        $gdtConversion->optimization_goal = $conversion['optimization_goal'];
        $gdtConversion->deep_behavior_optimization_goal = $conversion['deep_behavior_optimization_goal'];
        $gdtConversion->deep_worth_optimization_goal = $conversion['deep_worth_optimization_goal'];
        $gdtConversion->user_action_set_id = $conversion['user_action_set_id'];
        $gdtConversion->user_action_set_key = $conversion['user_action_set_key'];
        $gdtConversion->site_set_enable = $conversion['site_set_enable'];
        $gdtConversion->is_deleted = $conversion['is_deleted'];
        $gdtConversion->access_status = $conversion['access_status'];
        $gdtConversion->create_source_type = $conversion['create_source_type'];
        $gdtConversion->app_android_channel_package_id = $conversion['app_android_channel_package_id'];
        $gdtConversion->promoted_object_id = $conversion['promoted_object_id'];
        $gdtConversion->conversion_scene = $conversion['conversion_scene'] ?? '';
        $ret = $gdtConversion->save();

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
