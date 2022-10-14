<?php

namespace App\Models\Task;


class TaskGdtVideoUploadModel extends TaskGdtModel
{
    /**
     * 关联到模型的数据表
     *
     * @var string
     */
    protected $table = 'task_gdt_video_uploads';

    /**
     * 关联到模型数据表的主键
     *
     * @var string
     */
    protected $primaryKey = 'id';
}
