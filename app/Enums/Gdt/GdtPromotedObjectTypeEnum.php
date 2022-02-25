<?php

namespace App\Enums\Gdt;

class GdtPromotedObjectTypeEnum
{
    const PROMOTED_OBJECT_TYPE_APP_ANDROID = 'PROMOTED_OBJECT_TYPE_APP_ANDROID';
    const PROMOTED_OBJECT_TYPE_APP_IOS = 'PROMOTED_OBJECT_TYPE_APP_IOS';
    const PROMOTED_OBJECT_TYPE_LEAD_AD = 'PROMOTED_OBJECT_TYPE_LEAD_AD';

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
        ['id' => self::PROMOTED_OBJECT_TYPE_APP_ANDROID, 'name' => 'Android应用'],
        ['id' => self::PROMOTED_OBJECT_TYPE_APP_IOS, 'name' => 'IOS应用'],
        ['id' => self::PROMOTED_OBJECT_TYPE_LEAD_AD, 'name' => '销售线索'],
    ];
}
