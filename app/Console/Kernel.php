<?php

namespace App\Console;

use App\Common\Console\Queue\QueueClickCommand;
use App\Common\Helpers\Functions;
use App\Console\Commands\Gdt\GdtSyncAdCommand;
use App\Console\Commands\Gdt\GdtSyncAdCreativeCommand;
use App\Console\Commands\Gdt\GdtSyncAdgroupCommand;
use App\Console\Commands\Gdt\GdtSyncCampaignCommand;
use App\Console\Commands\Gdt\GdtSyncConversionCommand;
use App\Console\Commands\SyncChannelAdgroupCommand;
use App\Console\Commands\Task\TaskGdtSyncCommand;
use Illuminate\Console\Scheduling\Schedule;
use Laravel\Lumen\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    /**
     * The Artisan commands provided by your application.
     *
     * @var array
     */
    protected $commands = [

        // 队列
        QueueClickCommand::class,

        TaskGdtSyncCommand::class,

        // 广点通
        GdtSyncCampaignCommand::class,
        GdtSyncAdgroupCommand::class,
        GdtSyncAdCommand::class,
        GdtSyncAdCreativeCommand::class,
        GdtSyncConversionCommand::class,

        // 同步渠道-广告组
        SyncChannelAdgroupCommand::class,
    ];

    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
        // 队列
        $schedule->command('queue:click')->cron('* * * * *');


        // 同步渠道-广告组
        $schedule->command('sync_channel_adgroup --date=today')->cron('*/2 * * * *');

        // 正式
        if(Functions::isProduction()){
            // 广点通转化归因同步
            $schedule->command('gdt:sync_conversion --update_date=today')->cron('*/5 * * * *');


            // 广点通推广计划同步
            $schedule->command('gdt:sync_campaign --update_date=today --multi_chunk_size=1')->cron('*/30 * * * *');
            $schedule->command('gdt:sync_campaign --update_date=today --multi_chunk_size=1 --is_deleted=1')->cron('* */1 * * *');

            // 广点通广告组同步
            $schedule->command('gdt:sync_adgroup --update_date=today')->cron('*/2 * * * *');
            $schedule->command('gdt:sync_adgroup --update_date=today --is_deleted=1')->cron('* */1 * * *');
            $schedule->command('gdt:sync_adgroup --update_date=yesterday --key_suffix=yesterday')->cron('45-50 1 * * *');
            $schedule->command('gdt:sync_adgroup --update_date=yesterday --key_suffix=yesterday --is_deleted=1')->cron('45-50 2 * * *');

            // 广点通广告同步
            $schedule->command('gdt:sync_ad --update_date=today')->cron('*/2 * * * *');
            $schedule->command('gdt:sync_ad --update_date=today --is_deleted=1')->cron('* */1 * * *');
            $schedule->command('gdt:sync_ad --update_date=yesterday --key_suffix=yesterday')->cron('35-40 1 * * *');
            $schedule->command('gdt:sync_ad --update_date=yesterday --key_suffix=yesterday --is_deleted=1')->cron('35-40 2 * * *');

            // 广点通广告创意同步
            $schedule->command('gdt:sync_creative --update_date=today')->cron('*/2 * * * *');
            $schedule->command('gdt:sync_creative --update_date=today --is_deleted=1')->cron('* */1 * * *');
            $schedule->command('gdt:sync_creative --update_date=yesterday --key_suffix=yesterday')->cron('25-30 1 * * *');
            $schedule->command('gdt:sync_creative --update_date=yesterday --key_suffix=yesterday --is_deleted=1')->cron('25-30 2 * * *');

//            15224718
        }
    }
}
