<?php

namespace App\Console;

use App\Common\Console\Queue\QueueClickCommand;
use App\Common\Helpers\Functions;
use App\Console\Commands\Gdt\GdtSyncInfoCommand;
use App\Console\Commands\Gdt\GdtSyncReportCommand;
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
        GdtSyncInfoCommand::class,
        GdtSyncReportCommand::class,

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
            $schedule->command('gdt:sync_info --type=conversion --update_date=today')->cron('*/5 * * * *');


            // 广点通推广计划同步
            $schedule->command('gdt:sync_info --type=campaign --update_date=today --multi_chunk_size=1')->cron('*/30 * * * *');
            $schedule->command('gdt:sync_info --type=_campaign --update_date=today --multi_chunk_size=1 --is_deleted=1')->cron('* */1 * * *');

            // 广点通广告组同步
            $schedule->command('gdt:sync_info --type=_adgroup --update_date=today')->cron('*/2 * * * *');
            $schedule->command('gdt:sync_info --type=_adgroup --update_date=today --is_deleted=1')->cron('* */1 * * *');
            $schedule->command('gdt:sync_info --type=_adgroup --update_date=yesterday --key_suffix=yesterday')->cron('45-50 1 * * *');
            $schedule->command('gdt:sync_info --type=_adgroup --update_date=yesterday --key_suffix=yesterday --is_deleted=1')->cron('45-50 2 * * *');

            // 广点通广告同步
            $schedule->command('gdt:sync_info --type=_ad --update_date=today')->cron('*/2 * * * *');
            $schedule->command('gdt:sync_info --type=_ad --update_date=today --is_deleted=1')->cron('* */1 * * *');
            $schedule->command('gdt:sync_info --type=_ad --update_date=yesterday --key_suffix=yesterday')->cron('35-40 1 * * *');
            $schedule->command('gdt:sync_info --type=_ad --update_date=yesterday --key_suffix=yesterday --is_deleted=1')->cron('35-40 2 * * *');

            // 广点通广告创意同步
            $schedule->command('gdt:sync_info --type=_ad_creative --update_date=today')->cron('*/2 * * * *');
            $schedule->command('gdt:sync_info --type=_ad_creative --update_date=today --is_deleted=1')->cron('* */1 * * *');
            $schedule->command('gdt:sync_info --type=_ad_creative --update_date=yesterday --key_suffix=yesterday')->cron('25-30 1 * * *');
            $schedule->command('gdt:sync_info --type=_ad_creative --update_date=yesterday --key_suffix=yesterday --is_deleted=1')->cron('25-30 2 * * *');

            // 广点通账户报表同步
            $schedule->command('gdt:sync_report --type=account_by_day --date=today --has_history_cost=1 --key_suffix=has_history_cost')->cron('*/2 * * * *');
            $schedule->command('gdt:sync_report --type=account_by_day --date=today')->cron('15 * * * *');
            $schedule->command('gdt:sync_report --type=account_by_day --date=yesterday --key_suffix=yesterday')->cron('25-30 10 * * *');

            // 广点通广告报表同步
            $schedule->command('gdt:sync_report --type=ad_by_hour --date=today --run_by_account_cost=1 --multi_chunk_size=5')->cron('*/2 * * * *');
            $schedule->command('gdt:sync_report --type=ad_by_hour --date=yesterday --key_suffix=yesterday')->cron('10-15 9 * * *');

        }
    }
}
