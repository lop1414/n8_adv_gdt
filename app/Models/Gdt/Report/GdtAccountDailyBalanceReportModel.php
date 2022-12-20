<?php

namespace App\Models\Gdt\Report;

class GdtAccountDailyBalanceReportModel extends GdtReportModel
{
    /**
     * 关联到模型的数据表
     *
     * @var string
     */
    protected $table = 'gdt_account_daily_balance_reports';

    /**
     * 关联到模型数据表的主键
     *
     * @var string
     */
    protected $primaryKey = 'id';
}
