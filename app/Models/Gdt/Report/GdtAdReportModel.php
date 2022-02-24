<?php

namespace App\Models\Gdt\Report;

use Illuminate\Support\Facades\DB;

class GdtAdReportModel extends GdtReportModel
{
    /**
     * 关联到模型的数据表
     *
     * @var string
     */
    protected $table = 'gdt_ad_reports';

    /**
     * 关联到模型数据表的主键
     *
     * @var string
     */
    protected $primaryKey = 'id';

    /**
     * @param $query
     * @return mixed
     * 计算
     */
    public function scopeCompute($query){
        return $query->select(DB::raw("
                SUM(`cost`) `cost`,
                SUM(`valid_click_count`) `click`,
                SUM(`view_count`) `show`,
                SUM(`conversions_count`) `convert`,
                ROUND(SUM(`cost` / 100) / SUM(`view_count`) * 1000, 2) `show_cost`,
                ROUND(SUM(`cost` / 100) / SUM(`valid_click_count`), 2) `click_cost`,
                CONCAT(ROUND(SUM(`valid_click_count`) / SUM(`view_count`) * 100, 2), '%') `click_rate`,
                ROUND(SUM(`cost` / 100) / SUM(`conversions_count`), 2) `convert_cost`,
                CONCAT(ROUND(SUM(`conversions_count`) / SUM(`valid_click_count`) * 100, 2), '%') `convert_rate`
            "));
    }
}
