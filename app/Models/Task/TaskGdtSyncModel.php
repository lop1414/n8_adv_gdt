<?php

namespace App\Models\Task;

use App\Common\Models\SubTaskModel;

class TaskGdtSyncModel extends TaskGdtModel
{
    /**
     * 关联到模型的数据表
     *
     * @var string
     */
    protected $table = 'task_gdt_syncs';

    /**
     * 关联到模型数据表的主键
     *
     * @var string
     */
    protected $primaryKey = 'id';
}
