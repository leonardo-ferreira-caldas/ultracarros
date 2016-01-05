<?php

namespace App\Crawler;

use Carbon\Carbon;
use Symfony\Component\DomCrawler\Crawler as DomCrawler;
use App\Model\Crawler as CrawlerTable;
use GuzzleHttp\Client;
use Log;
use DB;
use Illuminate\Support\Str;
use Exception;

class Crawler {

    private $crawler;
    private $client;

    private $initialCrawlerDomain = "www.webmotors.com.br";
    private $initialCrawlerProtocol = "http";

    private $crawlingOffset = 40;
    private $ignoreUrlWords = ['javascript', 'mailto:', 'login'];

    private $regexProduct = '.*\/comprar\/.*portas.*';

    public function __construct(CrawlerTable $crawler, Client $client) {
        $this->crawler = $crawler;
        $this->client = $client;
    }

    public function getLinks($id) {
        if (!$this->crawler->count()) {
            $this->crawler->create(['url' => $this->formatUrl("")]);
        }

        $links = $this->crawler
            ->where('ind_crawled', '=', '0')
            ->where('failed_tries', '<', '5')
            ->skip(($id * $this->crawlingOffset) - $this->crawlingOffset)
            ->take($this->crawlingOffset);

        $result = $links->get();

        $links->update(['ind_crawled' => '1']);

        return $result;
    }

    private function formatUrl($url) {
        return sprintf("%s://%s/%s", $this->initialCrawlerProtocol, $this->initialCrawlerDomain, $url);
    }

    private function normalizeUrl($url) {
        if (Str::contains($url, $this->ignoreUrlWords)) {
            return false;
        } else if (Str::startsWith($url, $this->initialCrawlerProtocol)) {
            if (Str::contains($url, $this->initialCrawlerDomain)) {
                return $url;
            } else {
                return false;
            }
        }

        return $this->formatUrl(ltrim($url, "/"));
    }

    private function isProduct($url) {
        return (bool) preg_match('/' . $this->regexProduct . '/', $url);
    }

    public function crawl($id) {

        $links = $this->getLinks($id);

        $uuid = uniqid();
        Log::info("Starting to crawl $uuid... " . Carbon::now()->toDateTimeString());

        $counter = 0;

        foreach ($links as $link) {

            DB::beginTransaction();

            try {

                $request = $this->client->get($link->url);

                if ($request->getStatusCode() != 200) {
                    $link->ind_crawled = false;
                    $link->failed_tries = $link->failed_tries + 1;
                    $link->save();
                    Log::info("Erro de requisição: STATUS " . $request->getStatusCode());
                    continue;
                }

                $html = $request->getBody();

                $dom = new DomCrawler();
                $dom->addHtmlContent($html);

                if ($this->isProduct($link->url)) {
                    $spider = new Spider($link, $dom);
                    $spider->get();
                }

                foreach ($dom->filter("a") as $anchor) {
                    $url = $this->normalizeUrl($anchor->getAttribute("href"));

                    if ($url === false) {
                        continue;
                    }

                    $exists = CrawlerTable::where('url', '=', $url)->first();

                    if (!empty($exists)) {
                        continue;
                    }

                    $this->crawler->create([
                        'url' => $url
                    ]);

                    $counter++;

                }

                $link->failed_tries = 0;
                $link->error_log = null;
                $link->save();

                DB::commit();

            } catch (Exception $e) {
                DB::rollBack();

                Log::info($e->getMessage() . " / Url: " . $link->url);
                Log::info($e->getTraceAsString());

                $link->ind_crawled = false;
                $link->failed_tries = $link->failed_tries + 1;
                $link->error_log = $e->getMessage() . PHP_EOL . PHP_EOL . $e->getTraceAsString();
                $link->save();

            } finally {
                $dom = null;
                $html = null;
            }

        }

        Log::info("Crawling finished $uuid, Links inseridos: $counter!");
        session()->set('crawler', session()->get('crawler') - 1);

    }


}