<?php

namespace App\Enums\Gdt;

class GdtVideoStatusEnum
{
    const ADSTATUS_NORMAL = 'ADSTATUS_NORMAL';
    const ADSTATUS_DELETED = 'ADSTATUS_DELETED';

    /**
     * @var string
     * 名称
     */
    static public $name = '广点通视频状态';

    /**
     * @var array
     * 列表
     */
    static public $list = [
        ['id' => self::ADSTATUS_NORMAL, 'name' => '正常'],
        ['id' => self::ADSTATUS_DELETED, 'name' => '已删除']
    ];
}
