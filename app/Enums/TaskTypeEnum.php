<?php

namespace App\Enums;

use App\Common\Enums\SystemAliasEnum;
use App\Models\Task\TaskGdtSyncModel;
use App\Models\Task\TaskGdtVideoUploadModel;

class TaskTypeEnum
{
    const GDT_SYNC = 'GDT_SYNC';
    const GDT_VIDEO_UPLOAD = 'GDT_VIDEO_UPLOAD';

    /**
     * @var string
     * 名称
     */
    static public $name = '任务类型';

    /**
     * @var array
     * 列表
     */
    static public $list = [
        [
            'id' => self::GDT_SYNC,
            'name' => '广点通同步',
            'sub_model_class' => TaskGdtSyncModel::class,
            'system_alias' => SystemAliasEnum::ADV_GDT,
        ],
        [
            'id' => self::GDT_VIDEO_UPLOAD,
            'name' => '广点通视频上传',
            'sub_model_class' => TaskGdtVideoUploadModel::class,
            'system_alias' => SystemAliasEnum::ADV_GDT,
        ],
    ];
}
