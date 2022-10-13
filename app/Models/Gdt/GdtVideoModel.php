<?php

namespace App\Models\Gdt;

class GdtVideoModel extends GdtModel
{
    /**
     * 关联到模型的数据表
     *
     * @var string
     */
    protected $table = 'gdt_videos';


    /**
     * @var string
     * 主键数据类型
     */
    public $keyType = 'string';

    /**
     * @var bool
     * 是否自增
     */
    public $incrementing = false;
}
