<?php

namespace App\Console;

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
        Commands\WebCrawler::class,
        Commands\Cleaner::class,
        Commands\TestWebCrawler::class,
        Commands\ClearLog::class,
        Commands\CreateThumb::class
    ];

    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
        $crawlersToStart = 5;
        $crawlersToStart = $crawlersToStart - session()->get('crawler', 0);
        session()->set('crawler', $crawlersToStart);

        for ($i = 0; $i < $crawlersToStart; $i++) {
            $schedule->command("crawler:run {$i}")->everyMinute();
        }

        $schedule->command("crawler:clean")->cron('0 0 */2 * *');
        $schedule->command("log:clear")->hourly();
    }
}
