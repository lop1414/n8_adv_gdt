<?php

namespace App\Console\Commands\Task;

use App\Common\Console\BaseCommand;
use App\Common\Helpers\Functions;
use App\Enums\Gdt\GdtSyncTypeEnum;
use App\Services\Task\TaskGdtSyncService;

class TaskGdtSyncCommand extends BaseCommand
{
    /**
     * 命令行执行命令
     * @var string
     */
    protected $signature = 'task:gdt_sync {--type=}';

    /**
     * 命令描述
     *
     * @var string
     */
    protected $description = '广点通同步任务';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct(){
        parent::__construct();
    }

    /**
     * @throws \App\Common\Tools\CustomException
     * 处理
     */
    public function handle(){
        $type = strtoupper($this->option('type'));
        Functions::hasEnum(GdtSyncTypeEnum::class, $type);

        $taskOceanSyncService = new TaskGdtSyncService($type);
        $option = ['log' => true];
        $this->lockRun(
            [$taskOceanSyncService, 'run'],
            "task_ocean_sync_{$type}",
            43200,
            $option
        );
    }
}
