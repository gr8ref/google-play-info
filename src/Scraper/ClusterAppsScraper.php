<?php

declare(strict_types=1);

/**
 * @author   Ne-Lexa
 * @license  MIT
 *
 * @see      https://github.com/Ne-Lexa/google-play-info
 */

namespace rumi55\GPlay\Scraper;

use rumi55\GPlay\GPlayApps;
use rumi55\GPlay\Scraper\Extractor\AppsExtractor;
use rumi55\GPlay\Util\ScraperUtil;
use rumi55\HttpClient\ResponseHandlerInterface;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;
use function GuzzleHttp\Psr7\parse_query;

/**
 * @internal
 */
class ClusterAppsScraper implements ResponseHandlerInterface
{
    /**
     * @param RequestInterface  $request
     * @param ResponseInterface $response
     *
     * @return array
     */
    public function __invoke(RequestInterface $request, ResponseInterface $response): array
    {
        $scriptData = ScraperUtil::extractScriptData($response->getBody()->getContents());
        $scriptDataInfo = null;

        foreach ($scriptData as $scriptValue) {
            if (isset($scriptValue[0][1][0][0][0]) && \is_array($scriptValue[0][1][0][0][0])) {
                $scriptDataInfo = $scriptValue; // ds:3
                break;
            }
        }

        if ($scriptDataInfo === null) {
            return [[], null];
        }

        $query = parse_query($request->getUri()->getQuery());
        $locale = $query[GPlayApps::REQ_PARAM_LOCALE] ?? GPlayApps::DEFAULT_LOCALE;
        $country = $query[GPlayApps::REQ_PARAM_COUNTRY] ?? GPlayApps::DEFAULT_COUNTRY;

        $apps = [];

        foreach ($scriptDataInfo[0][1][0][0][0] as $data) {
            $apps[] = AppsExtractor::extractApp($data, $locale, $country);
        }

        $nextToken = $scriptDataInfo[0][1][0][0][7][1] ?? null;

        return [$apps, $nextToken];
    }
}
