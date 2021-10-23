<?php

namespace App\Models\Gdt;

class GdtConversionModel extends GdtModel
{
    /**
     * 关联到模型的数据表
     *
     * @var string
     */
    protected $table = 'gdt_conversions';


    /**
     * @var bool
     * 是否自增
     */
    public $incrementing = false;

}
