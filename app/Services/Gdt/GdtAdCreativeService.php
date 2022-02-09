<?php

namespace App\Services\Gdt;

use App\Common\Helpers\Functions;
use App\Common\Tools\CustomException;
use App\Models\Gdt\GdtAdcreativeModel;

class GdtAdCreativeService extends GdtService
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
        return $this->sdk->multiGetCreativeList($accounts, $page, $pageSize, $param);
    }

    /**
     * @param array $option
     * @return bool
     * @throws CustomException
     * 同步
     */
    public function sync($option = []){
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

        $accountGroup = $this->getAccountGroup($accountIds);

        $t = microtime(1);

        $pageSize = 100;
        foreach($accountGroup as $g){
            $creatives = $this->multiGetPageList($g, $pageSize, $param);
            Functions::consoleDump('count:'. count($creatives));
//dd($creatives);

            // 保存
            foreach($creatives as $creative) {
                $this->save($creative);
            }
        }

        $t = microtime(1) - $t;
        var_dump($t);

        return true;
    }

    /**
     * @param $creative
     * @return bool
     * 保存
     */
    public function save($creative){
        $gdtCreativeModel = new GdtAdcreativeModel();
        $gdtCreative = $gdtCreativeModel->where('id', $creative['adcreative_id'])->first();

        if(empty($gdtCreative)){
            $gdtCreative = new GdtAdcreativeModel();
        }

        $gdtCreative->id = $creative['adcreative_id'];
        $gdtCreative->name = $creative['adcreative_name'];
        $gdtCreative->account_id = $creative['account_id'];
        $gdtCreative->campaign_id = $creative['campaign_id'];
        $gdtCreative->adcreative_template_id = $creative['adcreative_template_id'];
        $gdtCreative->page_type = $creative['page_type'];
        $gdtCreative->link_page_type = $creative['link_page_type'];
        $gdtCreative->link_name_type = $creative['link_name_type'] ?? '';
        $gdtCreative->conversion_target_type = $creative['conversion_target_type'];
        $gdtCreative->site_set = $creative['site_set'];
        $gdtCreative->automatic_site_enabled = $creative['automatic_site_enabled'];
        $gdtCreative->promoted_object_type = $creative['promoted_object_type'];
        $gdtCreative->promoted_object_id = $creative['promoted_object_id'];
        $gdtCreative->is_deleted = $creative['is_deleted'];
        $gdtCreative->is_dynamic_creative = $creative['is_dynamic_creative'];
        $gdtCreative->component_id = $creative['component_id'];
        $gdtCreative->enable_breakthrough_siteset = $creative['enable_breakthrough_siteset'];
        $gdtCreative->creative_template_version_type = $creative['creative_template_version_type'];
        $gdtCreative->created_time = date('Y-m-d H:i:s',$creative['created_time']);
        $gdtCreative->last_modified_time = date('Y-m-d H:i:s',$creative['last_modified_time']);
        $gdtCreative->extends = $creative;
        $ret = $gdtCreative->save();

        if($ret && isset($creative['adcreative_elements']['image'])){
            // 添加关联关系
            (new GdtImageService())->relationAccount($gdtCreative['account_id'],$creative['adcreative_elements']['image']);
        }

        return $ret;
    }
}
