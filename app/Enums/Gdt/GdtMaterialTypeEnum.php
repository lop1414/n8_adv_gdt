<?php

namespace App\Enums\Gdt;

class GdtMaterialTypeEnum
{
    const IMAGE = 'IMAGE';
    const VIDEO = 'VIDEO';

    /**
     * @var string
     * 名称
     */
    static public $name = '广点通素材类型';

    /**
     * @var array
     * 列表
     */
    static public $list = [
        ['id' => self::IMAGE, 'name' => '图片'],
        ['id' => self::VIDEO, 'name' => '视频']
    ];
}
