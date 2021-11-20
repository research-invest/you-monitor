<?php
/**
 * composer dump-autoload
 */
namespace App\Console;

use App\Console\Commands\GetNewVideoChannelsByRss;
use App\Console\Commands\GetVideoInfo;
use App\Console\Commands\GetChannelInfo;
use App\Console\Commands\Misc;
use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    /**
     * The Artisan commands provided by your application.
     *
     * @var array
     */
    protected $commands = [
        GetNewVideoChannelsByRss::class,
        GetVideoInfo::class,
        GetChannelInfo::class,
        Misc::class,
    ];

    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
        $schedule->command(GetNewVideoChannelsByRss::class)->everyTenMinutes();
        $schedule->command(GetVideoInfo::class)->everyThirtyMinutes();
        $schedule->command(GetChannelInfo::class)->everyTwoHours();
    }

    /**
     * Register the commands for the application.
     *
     * @return void
     */
    protected function commands()
    {
        $this->load(__DIR__.'/Commands');

        require base_path('routes/console.php');
    }
}
