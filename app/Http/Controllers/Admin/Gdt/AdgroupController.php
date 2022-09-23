<?php

namespace App\Http\Controllers\Admin\Gdt;

use App\Common\Helpers\Functions;
use App\Common\Services\SystemApi\UnionApiService;
use App\Models\Gdt\GdtAdgroupModel;
use Illuminate\Support\Facades\DB;

class AdgroupController extends GdtController
{
    /**
     * constructor.
     */
    public function __construct()
    {
        $this->model = new GdtAdgroupModel();

        parent::__construct();
    }

    /**
     * 列表预处理
     */
    public function selectPrepare(){
        parent::selectPrepare();

        // 默认排序
        if(empty($this->curdService->requestData['order_by'])){
            $this->curdService->setOrderBy('last_modified_time', 'desc');
        }

        $this->curdService->selectQueryBefore(function(){
            $this->curdService->customBuilder(function($builder){
                // 关键词
                $keyword = $this->curdService->requestData['keyword'] ?? '';
                if(!empty($keyword)){
                    $builder->whereRaw("(adgroup_id LIKE '%{$keyword}%' OR name LIKE '%{$keyword}%')");
                }

            });
        });

        $this->curdService->selectQueryAfter(function(){
            $unionApiService = new UnionApiService();
            $channelMap = [];
            foreach($this->curdService->responseData['list'] as $v){
                // 账户
                $v->gdt_account;

                // 广告组扩展
                $v->gdt_adgroup_extends;
                if(!empty($v->gdt_adgroup_extends)){
                    // 策略
                    $v->gdt_adgroup_extends->convert_callback_strategy;
                    // 策略组
                    $v->gdt_adgroup_extends->convert_callback_strategy_group;
                }


                if(!empty($v->channel_adgroup)){
                    $channelId = $v->channel_adgroup->channel_id;
                    if(empty($channelMap[$channelId])){
                        $channelMap[$channelId] = $unionApiService->apiReadChannel(['id' => $channelId]);
                    }
                    $v->channel = $channelMap[$channelId];
                }
                // 关联报表
                //$v->report = $v->ocean_creative_reports()->compute()->first();

                unset($v->extends);
            }
        });
    }

    /**
     * 详情预处理
     */
    public function readPrepare(){
        parent::readPrepare();

        $this->curdService->findAfter(function(){
            // 关联广点通账户
            $this->curdService->findData->gdt_account;

            $this->curdService->getModel()->expandExtendsField($this->curdService->findData);
        });
    }
}
