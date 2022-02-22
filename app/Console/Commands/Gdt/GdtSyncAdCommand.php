<?php

namespace App\Console\Commands\Gdt;

use App\Services\Gdt\GdtAdService;

class GdtSyncAdCommand extends GdtSyncBaseCommand
{
    /**
     * 命令行执行命令
     * @var string
     */
    protected $signature = 'gdt:sync_ad  {--update_date=}  {--account_ids=} {--is_deleted=} {--multi_chunk_size=} {--key_suffix=}';

    /**
     * 命令描述
     *
     * @var string
     */
    protected $description = '同步广点通广告';

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
        $param = $this->option();

        // 锁 key
        $lockKey = $this->getLockKey('gdt_sync_ad',$param);
        $param = $this->filterParam($param);

        $gdtAdService = new GdtAdService();
        $option = ['log' => true];
        $this->lockRun(
            [$gdtAdService, 'sync'],
            $lockKey,
            43200,
            $option,
            $param
        );
    }
}
