<?php

namespace App\Enums\Gdt;

class GdtFundTypeEnum
{
    const FUND_TYPE_CASH = 'FUND_TYPE_CASH';
    const FUND_TYPE_GIFT = 'FUND_TYPE_GIFT';
    const FUND_TYPE_SHARED = 'FUND_TYPE_SHARED';
    const FUND_TYPE_BANK = 'FUND_TYPE_BANK';
    const FUND_TYPE_MP_CASH = 'FUND_TYPE_MP_CASH';
    const FUND_TYPE_MP_BANK = 'FUND_TYPE_MP_BANK';
    const FUND_TYPE_MP_GIFT = 'FUND_TYPE_MP_GIFT';
    const FUND_TYPE_CREDIT_ROLL = 'FUND_TYPE_CREDIT_ROLL';
    const FUND_TYPE_CREDIT_TEMPORARY = 'FUND_TYPE_CREDIT_TEMPORARY';
    const FUND_TYPE_COMPENSATE_VIRTUAL = 'FUND_TYPE_COMPENSATE_VIRTUAL';
    const FUND_TYPE_INTERNAL_QUOTA = 'FUND_TYPE_INTERNAL_QUOTA';
    const FUND_TYPE_UNSUPPORTED = 'FUND_TYPE_UNSUPPORTED';
    const FUND_TYPE_SPECIAL_GIFT = 'FUND_TYPE_SPECIAL_GIFT';
    const FUND_TYPE_MP_GAME_DEVELOPER_WORKING_FUND = 'FUND_TYPE_MP_GAME_DEVELOPER_WORKING_FUND';
    const FUND_TYPE_MP_GAME_DEVELOPER_GIFT = 'FUND_TYPE_MP_GAME_DEVELOPER_GIFT';
    const FUND_TYPE_FLOW_SOURCE_AD_FUND = 'FUND_TYPE_FLOW_SOURCE_AD_FUND';
    const FUND_TYPE_ANDROID_ORIENTED_GIFT = 'FUND_TYPE_ANDROID_ORIENTED_GIFT';
    const FUND_TYPE_LOCATION_PROMOTION_REWARDS = 'FUND_TYPE_LOCATION_PROMOTION_REWARDS';

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
        ['id' => self::FUND_TYPE_CASH, 'name' => '现金账户'],
        ['id' => self::FUND_TYPE_GIFT, 'name' => '赠送账户'],
        ['id' => self::FUND_TYPE_SHARED, 'name' => '分成账户'],
        ['id' => self::FUND_TYPE_BANK, 'name' => '银证账户'],
        ['id' => self::FUND_TYPE_MP_CASH, 'name' => '微信现金账户'],
        ['id' => self::FUND_TYPE_MP_BANK, 'name' => '微信银证账户'],
        ['id' => self::FUND_TYPE_MP_GIFT, 'name' => '微信赠送账户'],
        ['id' => self::FUND_TYPE_CREDIT_ROLL, 'name' => '竞价信用账户'],
        ['id' => self::FUND_TYPE_CREDIT_TEMPORARY, 'name' => '竞价临时信用账户'],
        ['id' => self::FUND_TYPE_COMPENSATE_VIRTUAL, 'name' => '补偿虚拟金账户'],
        ['id' => self::FUND_TYPE_INTERNAL_QUOTA, 'name' => '内部领用金'],
        ['id' => self::FUND_TYPE_UNSUPPORTED, 'name' => '不支持'],
        ['id' => self::FUND_TYPE_SPECIAL_GIFT, 'name' => '专用账户'],
        ['id' => self::FUND_TYPE_MP_GAME_DEVELOPER_WORKING_FUND, 'name' => '微信小游戏内购快周转'],
        ['id' => self::FUND_TYPE_MP_GAME_DEVELOPER_GIFT, 'name' => '微信小游戏内购赠送金'],
        ['id' => self::FUND_TYPE_FLOW_SOURCE_AD_FUND, 'name' => '流量主广告金'],
        ['id' => self::FUND_TYPE_ANDROID_ORIENTED_GIFT, 'name' => '安卓定向赠送金'],
        ['id' => self::FUND_TYPE_LOCATION_PROMOTION_REWARDS, 'name' => '附近推激励金'],
    ];
}
