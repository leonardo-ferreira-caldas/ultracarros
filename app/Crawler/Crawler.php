<?php

namespace App\Crawler;

use Carbon\Carbon;
use Illuminate\Support\Collection;
use Symfony\Component\DomCrawler\Crawler as DomCrawler;
use App\Model\Crawler as CrawlerTable;
use GuzzleHttp\Client;
use Log;
use Illuminate\Support\Str;
use Exception;

class Crawler {

    private $crawler;
    private $client;

    private $initialCrawlerDomain = "www.webmotors.com.br";
    private $initialCrawlerProtocol = "http";
    private $crawlingOffset = 25;

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
            ->skip(($id * $this->crawlingOffset) - $this->crawlingOffset)
            ->take($this->crawlingOffset)->lockForUpdate();

        $result = $links->get();

        $links->update(['ind_crawled' => '1']);

        return $result;
    }

    private function formatUrl($url) {
        return sprintf("%s://%s/%s", $this->initialCrawlerProtocol, $this->initialCrawlerDomain, $url);
    }

    private function normalizeUrl($url) {
        if (Str::startsWith($url, $this->initialCrawlerProtocol)) {
            if (Str::contains($url, $this->initialCrawlerDomain)) {
                return $url;
            } else {
                return false;
            }
        }

        return $this->formatUrl(ltrim($url, "/"));
    }

    private function insert(Collection $links) {
        $arrLinks = $links->unique()->toArray();

        $counter = 0;

        foreach ($arrLinks as $url) {
            try {
                $this->crawler->create([
                    'url' => $url
                ]);
                $counter++;
            } catch (Exception $e) {}
        }

        Log::info("Links inseridos: " . $counter);
    }

    public function crawl($id) {

        $links = $this->getLinks($id);

        $uuid = uniqid();
        Log::info("Starting to crawl $uuid... " . Carbon::now()->toDateTimeString());

        $linksEncontrados = collect();

        foreach ($links as $link) {

            try {
                $request = $this->client->get($link->url);

//                if ($request->getStatusCode() != 200) {
//                    continue;
//                }

                $html = $request->getBody();

            } catch (Exception $e) {
                Log::info($e->getMessage());
                continue;
            }

            $dom = new DomCrawler();
            $dom->addHtmlContent($html);

            foreach ($dom->filter("a") as $anchor) {
                $url = $this->normalizeUrl($anchor->getAttribute("href"));

                if ($url === false) {
                    continue;
                }

                $linksEncontrados->push($url);

            }

        }

        $this->insert($linksEncontrados);

        Log::info("Crawling finished $uuid... " . Carbon::now()->toDateTimeString());

    }

}