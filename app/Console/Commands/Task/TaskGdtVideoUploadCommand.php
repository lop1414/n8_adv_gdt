<?php

namespace App\Console\Commands\Task;

use App\Common\Console\BaseCommand;
use App\Services\Task\TaskGdtVideoUploadService;

class TaskGdtVideoUploadCommand extends BaseCommand
{
    /**
     * 命令行执行命令
     * @var string
     */
    protected $signature = 'task:gdt_video_upload';

    /**
     * 命令描述
     *
     * @var string
     */
    protected $description = '广点通视频上传任务';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct(){
        parent::__construct();
    }

    /**
     * 处理
     */
    public function handle(){
        $oceanVideoUploadTaskService = new TaskGdtVideoUploadService();
        $option = ['log' => true];
        $this->lockRun(
            [$oceanVideoUploadTaskService, 'run'],
            'task_gdt_video_upload',
            43200,
            $option
        );
    }
}
