<?php

namespace App\Console\Commands;

use App\Crawler\Spider;
use Illuminate\Console\Command;
use App\Model\Crawler;
use GuzzleHttp\Client;
use Symfony\Component\DomCrawler\Crawler as DomCrawler;

class TestWebCrawler extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'crawler:test {url}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

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
    public function handle(Client $client)
    {
        Crawler::create(['url' => $this->argument('url')]);

        return;
        Crawler::where('url', '=', $this->argument('url'))->delete();
        $crawler = Crawler::create(['url' => $this->argument('url')]);
        $html = $client->get($this->argument('url'))->getBody();
        $dom = new DomCrawler();
        $dom->addHtmlContent($html);
        $spider = new Spider($crawler, $dom);
        $spider->get();
    }
}
