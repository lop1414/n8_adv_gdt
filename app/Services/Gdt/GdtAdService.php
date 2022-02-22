<?php

namespace App\Services\Gdt;

use App\Common\Helpers\Functions;
use App\Common\Tools\CustomException;
use App\Models\Gdt\GdtAdModel;

class GdtAdService extends GdtService
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
        return $this->sdk->multiGetAdList($accounts, $page, $pageSize, $param);
    }

    /**
     * @param array $param
     * @return bool
     * @throws CustomException
     * 同步
     */
    public function sync($param = []){

        $accountGroup = $this->getAccountGroup($param['account_ids']);

        $t = microtime(1);

        $pageSize = 100;
        foreach($accountGroup as $g){
            $ads = $this->multiGetPageList($g, $pageSize, $param);
            Functions::consoleDump('count:'. count($ads));
            // 保存
            foreach($ads as $ad) {
                $this->save($ad);
            }
        }

        $t = microtime(1) - $t;
        var_dump($t);

        return true;
    }

    /**
     * @param $ad
     * @return bool
     * 保存
     */
    public function save($ad){
        $gdtAdModel = new GdtAdModel();
        $gdtAd = $gdtAdModel->where('id', $ad['ad_id'])->first();

        if(empty($gdtAd)){
            $gdtAd = new GdtAdModel();
        }

        $gdtAd->id = $ad['ad_id'];
        $gdtAd->name = $ad['ad_name'];
        $gdtAd->account_id = $ad['account_id'];
        $gdtAd->campaign_id = $ad['campaign_id'];
        $gdtAd->adgroup_id = $ad['adgroup_id'];
        $gdtAd->adcreative_id = $ad['adcreative_id'];
        $gdtAd->configured_status = $ad['configured_status'];
        $gdtAd->system_status = $ad['system_status'];
        $gdtAd->audit_spec = $ad['audit_spec'];
        $gdtAd->click_tracking_url = $ad['click_tracking_url'];
        $gdtAd->is_deleted = $ad['is_deleted'];
        $gdtAd->is_dynamic_creative = $ad['is_dynamic_creative'];
        $gdtAd->created_time = date('Y-m-d H:i:s',$ad['created_time']);
        $gdtAd->last_modified_time = date('Y-m-d H:i:s',$ad['last_modified_time']);
        $ret = $gdtAd->save();
        return $ret;
    }
}
