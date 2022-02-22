<?php

namespace App\Console\Commands\Gdt;

use App\Services\Gdt\GdtConversionService;

class GdtSyncConversionCommand extends GdtSyncBaseCommand
{
    /**
     * 命令行执行命令
     * @var string
     */
    protected $signature = 'gdt:sync_conversion  {--update_date=} {--account_ids=} {--multi_chunk_size=} {--key_suffix=}';

    /**
     * 命令描述
     *
     * @var string
     */
    protected $description = '同步广点通转化归因';

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
        $lockKey = $this->getLockKey('gdt_sync_conversion',$param);

        $param = $this->filterParam($param);
        $gdtConversionService = new GdtConversionService();
        $option = ['log' => true];
        $this->lockRun(
            [$gdtConversionService, 'sync'],
            $lockKey,
            43200,
            $option,
            $param
        );
    }
}
