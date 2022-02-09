<?php

namespace App\Enums\Gdt;

class GdtSyncTypeEnum
{
    const VIDEO = 'VIDEO';
    const ACCOUNT = 'ACCOUNT';

    /**
     * @var string
     * 名称
     */
    static public $name = '广点通同步类型';

    /**
     * @var array
     * 列表
     */
    static public $list = [
        ['id' => self::VIDEO, 'name' => '视频'],
        ['id' => self::ACCOUNT, 'name' => '账户'],
    ];
}
