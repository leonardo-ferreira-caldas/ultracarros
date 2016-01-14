<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use DB;

class Cleaner extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'crawler:clean';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Clean the crawler flags';

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
        DB::table('crawler')
            ->where("ind_crawled", '=', '1')
            ->where("failed_tries", '<', '5')
            ->update(['ind_crawled' => 1]);
    }
}
