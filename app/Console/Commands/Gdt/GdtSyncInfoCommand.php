<?php

namespace App\Console\Commands\Gdt;

use App\Common\Console\BaseCommand;
use App\Common\Helpers\Functions;
use App\Common\Tools\CustomException;
use App\Services\Gdt\GdtAdCreativeService;
use App\Services\Gdt\GdtAdgroupService;
use App\Services\Gdt\GdtAdService;
use App\Services\Gdt\GdtCampaignService;
use App\Services\Gdt\GdtConversionService;
use App\Services\Gdt\GdtImageService;
use App\Services\Gdt\GdtVideoService;

class GdtSyncInfoCommand extends BaseCommand
{
    /**
     * 命令行执行命令
     * @var string
     */
    protected $signature = 'gdt:sync_info {--type=}  {--update_date=}  {--account_ids=} {--is_deleted=} {--multi_chunk_size=} {--key_suffix=}';

    /**
     * 命令描述
     *
     * @var string
     */
    protected $description = '同步广点通信息';

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

        $option = $this->option();
        $param = ['account_ids' => []];

        // 更新日期
        !empty($option['update_date']) && $param['update_date'] = Functions::getDate($option['update_date']);

        // 账户id
        if(!empty($option['account_ids'])){
            $param['account_ids'] = explode(",", $option['account_ids']);
        }

        // 同步删除数据
        !empty($option['is_deleted']) && $param['is_deleted'] = $option['update_date'];

        // 并发分片大小
        !empty($option['multi_chunk_size']) && $param['multi_chunk_size'] = min(intval($option['multi_chunk_size']), 8);

        // 锁 key
        $lockKey = 'gdt_sync_'.$option['type'];
        !empty($option['key_suffix']) && $lockKey .= '_'. trim($option['key_suffix']);

        $service = $this->getServices($option['type']);
        $this->lockRun([$service, 'sync'], $lockKey, 43200, ['log' => true], $param);
    }



    public function getServices($type){
        switch ($type){
            case 'campaign':
                echo "同步广点通推广计划\n";
                $service = new GdtCampaignService();
                break;
            case 'adgroup':
                echo "同步广点通广告组\n";
                $service = new GdtAdgroupService();
                break;
            case 'ad_creative':
                echo "同步广点通广告创意\n";
                $service = new GdtAdCreativeService();
                break;
            case 'ad':
                echo "同步广点通广告\n";
                $service = new GdtAdService();
                break;
            case 'conversion':
                echo "同步广点通转化归因\n";
                //已删除的无法获取
                //无法根据时间过滤获取
                $service = new GdtConversionService();
                break;
            case 'image':
                echo "同步广点通图片\n";
                $service = new GdtImageService();
                break;
            case 'video':
                echo "同步广点通视频\n";
                $service = new GdtVideoService();
                break;
            default:
                throw new CustomException([
                    'code' => 'TYPE_PARAM_INVALID',
                    'message' => 'type 无效',
                ]);
        }
        return $service;
    }
}
