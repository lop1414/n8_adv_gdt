<?php

namespace App\Enums\Gdt;

class GdtVideoTypeEnum
{
    const MEDIA_TYPE_MP4 = 'MEDIA_TYPE_MP4';
    const MEDIA_TYPE_AVI = 'MEDIA_TYPE_AVI';
    const MEDIA_TYPE_MOV = 'MEDIA_TYPE_MOV';
    const MEDIA_TYPE_FLV = 'MEDIA_TYPE_FLV';

    /**
     * @var string
     * 名称
     */
    static public $name = '广点通视频类型';

    /**
     * @var array
     * 列表
     */
    static public $list = [
        ['id' => self::MEDIA_TYPE_MP4, 'name' => 'mp4'],
        ['id' => self::MEDIA_TYPE_AVI, 'name' => 'avi'],
        ['id' => self::MEDIA_TYPE_MOV, 'name' => 'mov'],
        ['id' => self::MEDIA_TYPE_FLV, 'name' => 'flv']
    ];
}
