<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Log;

class InformationCrawler extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'crawler:info';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Rasteja pelos links/urls encontrados pelo LinkCrawler';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        Log::info("Scheduler working...");
    }
}
