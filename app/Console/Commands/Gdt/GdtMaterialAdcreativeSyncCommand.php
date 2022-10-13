<?php

namespace App\Console\Commands\Gdt;

use App\Common\Console\BaseCommand;
use App\Services\Gdt\GdtMaterialAdcreativeService;


class GdtMaterialAdcreativeSyncCommand extends BaseCommand
{
    /**
     * 命令行执行命令
     * @var string
     */
    protected $signature = 'gdt:material_adcreative_sync {--date=}';

    /**
     * 命令描述
     *
     * @var string
     */
    protected $description = '广点通素材创意关联同步';

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

        $gdtMaterialAdCreativeService = new GdtMaterialAdcreativeService();
        $option = ['log' => true];
        $this->lockRun(
            [$gdtMaterialAdCreativeService, 'sync'],
            'gdt_material_adcreative_sync',
            43200,
            $option,
            $param
        );
    }
}
