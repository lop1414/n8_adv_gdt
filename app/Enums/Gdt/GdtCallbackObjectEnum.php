<?php

namespace App\Enums\Gdt;

class GdtCallbackObjectEnum
{
    const FIRST_PAY_NO_LIMIT = 'read_0';
    const FIRST_PAY_24 = 'read_1';
    const ALl_24 = 'read_2';
    const FIRST_PAY_TODAY = 'read_3';
    const All_TODAY = 'read_4';
    const FIRST_PAY_72 = 'read_5';
    const ALL_72 = 'read_6';


    /**
     * @var string
     * 名称
     */
    static public $name = '转化回传时间';

    /**
     * @var array
     * 列表
     */
    static public $list = [
        ['id' => self::FIRST_PAY_NO_LIMIT, 'name' => '不限时首充'],
        ['id' => self::FIRST_PAY_24, 'name' => '24小时首充'],
        ['id' => self::ALl_24, 'name' => '24小时所有'],
        ['id' => self::FIRST_PAY_TODAY, 'name' => '当天首充'],
        ['id' => self::All_TODAY, 'name' => '当天所有'],
        ['id' => self::FIRST_PAY_72, 'name' => '72小时首充'],
        ['id' => self::ALL_72, 'name' => '72小时所有'],
    ];
}
