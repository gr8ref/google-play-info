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
use rumi55\GPlay\Model\App;
use rumi55\GPlay\Util\ScraperUtil;
use rumi55\HttpClient\ResponseHandlerInterface;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;

/**
 * @internal
 */
class ClusterPagesFromListAppsScraper implements ResponseHandlerInterface
{
    /**
     * @param RequestInterface  $request
     * @param ResponseInterface $response
     *
     * @return App[]
     */
    public function __invoke(RequestInterface $request, ResponseInterface $response)
    {
        $scriptData = ScraperUtil::extractScriptData($response->getBody()->getContents());

        $results = [];

        foreach ($scriptData as $k => $v) {
            if (isset($v[0][1][0][0][1], $v[0][1][0][0][3][4][2])) {
                foreach ($v[0][1] as $a) {
                    if (isset($a[0][1], $a[0][3][4][2])) {
                        $results[] = [
                            'name' => trim($a[0][1]),
                            'url' => GPlayApps::GOOGLE_PLAY_URL . $a[0][3][4][2],
                        ];
                    }
                }
                break;
            }
        }

        return $results;
    }
}
