<?php

namespace App\Enums\Gdt;

class GdtAdStatusEnum
{
    const STATUS_UNKNOWN = 'STATUS_UNKNOWN';
    const STATUS_PENDING = 'STATUS_PENDING';
    const STATUS_DENIED = 'STATUS_DENIED';
    const STATUS_SUSPEND = 'STATUS_SUSPEND';
    const STATUS_ACTIVE = 'STATUS_ACTIVE';
    const STATUS_PART_ACTIVE = 'STATUS_PART_ACTIVE';

    /**
     * @var string
     * 名称
     */
    static public $name = '广点通广告组状态';

    /**
     * @var array
     * 列表
     */
    static public $list = [
        ['id' => self::STATUS_UNKNOWN, 'name' => '未知状态'],
        ['id' => self::STATUS_PENDING, 'name' => '审核中'],
        ['id' => self::STATUS_DENIED, 'name' => '审核不通过'],
        ['id' => self::STATUS_SUSPEND, 'name' => '暂停中'],
        ['id' => self::STATUS_ACTIVE, 'name' => '投放中'],
        ['id' => self::STATUS_PART_ACTIVE, 'name' => '部分投放中'],
    ];
}
