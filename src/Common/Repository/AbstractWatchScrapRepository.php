<?php

namespace Hisham\CodeLab\Common\Repository;

use Hisham\CodeLab\Common\Enum\HttpMethod;
use Symfony\Component\BrowserKit\HttpBrowser;
use Symfony\Component\DomCrawler\Crawler;

/**
 * Client to do WebScraping
 */
abstract class AbstractWatchScrapRepository
{
    private readonly string $baseUrl;

    public function __construct(
        private readonly HttpBrowser $client,
    )
    {
        $this->baseUrl = 'https://php.watch/';
    }

    /**
     * Searches in a specific page the content
     *
     * @param string $pathname
     *
     * @return Crawler
     */
    protected function fetch(string $pathname): Crawler
    {
        $pathname = ltrim($pathname, "/");
        $url = "{$this->baseUrl}{$pathname}";

        return $this->client->request(HttpMethod::GET->value, $url);
    }

}
