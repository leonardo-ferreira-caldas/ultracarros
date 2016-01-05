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
        Commands\TestWebCrawler::class
    ];

    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
        for ($i = 0; $i < 10; $i++) {
            $schedule->command("crawler:run {$i}")->everyMinute();
        }

        $schedule->command("crawler:clean")->weekly();
    }
}
