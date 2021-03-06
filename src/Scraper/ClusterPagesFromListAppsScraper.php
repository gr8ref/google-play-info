<?php

declare(strict_types=1);

/**
 * @author   gr8ref
 * @license  MIT
 *
 * @see      https://github.com/gr8ref/google-play-info
 */

namespace gr8ref\GPlay\Scraper;

use gr8ref\GPlay\GPlayApps;
use gr8ref\GPlay\Model\App;
use gr8ref\GPlay\Util\ScraperUtil;
use gr8ref\HttpClient\ResponseHandlerInterface;
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
