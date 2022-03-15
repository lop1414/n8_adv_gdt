<?php

namespace App\Http\Controllers\Admin\Gdt;

use App\Common\Helpers\Functions;
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

                // 时间范围
                $startDate = $this->curdService->requestData['start_date'] ?? date('Y-m-d');
                $endDate = $this->curdService->requestData['end_date'] ?? date('Y-m-d');
                Functions::dateCheck($startDate);
                Functions::dateCheck($endDate);

                $report = DB::table('gdt_ad_reports')
                    ->whereBetween('stat_datetime', ["{$startDate} 00:00:00", "{$endDate} 23:59:59"])
                    ->select(DB::raw("
                        gdt_adgroups.id,
                        ROUND(SUM(`cost` / 100), 2) `cost`,
                        SUM(`valid_click_count`) `click`,
                        SUM(`view_count`) `show`,
                        SUM(`conversions_count`) `convert`,
                        ROUND(SUM(`cost` / 100) / SUM(`view_count`) * 1000, 2) `show_cost`,
                        ROUND(SUM(`cost` / 100) / SUM(`valid_click_count`), 2) `click_cost`,
                        ROUND(SUM(`valid_click_count`) / SUM(`view_count`), 4) `click_rate`,
                        ROUND(SUM(`cost` / 100) / SUM(`conversions_count`), 2) `convert_cost`,
                        ROUND(SUM(`conversions_count`) / SUM(`valid_click_count`), 4) `convert_rate`
                    "))
                    ->groupBy('adgroup_id');

                $builder->LeftjoinSub($report, 'report', function($join){
                    $join->on('gdt_adgroups.id', '=', 'report.adgroup_id');
                });
            });
        });

        $this->curdService->selectQueryAfter(function(){
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
