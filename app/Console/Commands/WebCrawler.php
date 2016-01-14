<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Crawler\Crawler;

class WebCrawler extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'crawler:run {id}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Rasteja um website procurando links';

    protected $crawler;

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct(Crawler $crawler)
    {
        $this->crawler = $crawler;
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $this->comment($this->crawler->crawl($this->argument('id')));
    }
}
