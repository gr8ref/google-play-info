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
use gr8ref\GPlay\Model\AppId;
use gr8ref\GPlay\Util\ScraperUtil;
use gr8ref\HttpClient\ResponseHandlerInterface;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;

/**
 * @internal
 */
class FindSimilarAppsUrlScraper implements ResponseHandlerInterface
{
    /** @var AppId */
    private $appId;

    /**
     * SimilarScraper constructor.
     *
     * @param AppId $appId
     */
    public function __construct(AppId $appId)
    {
        $this->appId = $appId;
    }

    /**
     * @param RequestInterface  $request
     * @param ResponseInterface $response
     *
     * @return string|null
     */
    public function __invoke(RequestInterface $request, ResponseInterface $response): ?string
    {
        $scriptData = ScraperUtil::extractScriptData($response->getBody()->getContents());

        foreach ($scriptData as $key => $scriptValue) {
            if (isset($scriptValue[1][1][0][0][3][4][2])) {
                return GPlayApps::GOOGLE_PLAY_URL . $scriptValue[1][1][0][0][3][4][2] .
                    '&' . GPlayApps::REQ_PARAM_LOCALE . '=' . urlencode($this->appId->getLocale()) .
                    '&' . GPlayApps::REQ_PARAM_COUNTRY . '=' . urlencode($this->appId->getCountry());
                break;
            }
        }

        return null;
    }
}
