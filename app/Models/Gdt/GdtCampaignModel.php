<?php

namespace App\Models\Gdt;

class GdtCampaignModel extends GdtModel
{
    /**
     * 关联到模型的数据表
     *
     * @var string
     */
    protected $table = 'gdt_campaigns';


    /**
     * @var bool
     * 是否自增
     */
    public $incrementing = false;



    /**
     * @param $value
     * @return float|int
     */
    public function getDailyBudgetAttribute($value)
    {

        return $value/100;
    }

    /**
     * @param $value
     * 属性修饰器
     */
    public function setDailyBudgetAttribute($value)
    {
        $this->attributes['daily_budget'] = $value * 100;
    }





    /**
     * @param $value
     * @return float|int
     */
    public function getTotalBudgetAttribute($value)
    {

        return $value/100;
    }

    /**
     * @param $value
     * 属性修饰器
     */
    public function setTotalBudgetAttribute($value)
    {
        $this->attributes['total_budget'] = $value * 100;
    }
}
