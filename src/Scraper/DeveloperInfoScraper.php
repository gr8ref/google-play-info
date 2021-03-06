<?php

declare(strict_types=1);

/**
 * @author   gr8ref
 * @license  MIT
 *
 * @see      https://github.com/gr8ref/google-play-info
 */

namespace gr8ref\GPlay\Scraper;

use gr8ref\GPlay\Exception\GooglePlayException;
use gr8ref\GPlay\GPlayApps;
use gr8ref\GPlay\Model\Developer;
use gr8ref\GPlay\Model\GoogleImage;
use gr8ref\GPlay\Util\ScraperUtil;
use gr8ref\HttpClient\ResponseHandlerInterface;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;
use function GuzzleHttp\Psr7\parse_query;

/**
 * @internal
 */
class DeveloperInfoScraper implements ResponseHandlerInterface
{
    /**
     * @param RequestInterface  $request
     * @param ResponseInterface $response
     *
     * @throws GooglePlayException
     *
     * @return mixed
     */
    public function __invoke(RequestInterface $request, ResponseInterface $response)
    {
        $query = parse_query($request->getUri()->getQuery());
        $developerId = $query[GPlayApps::REQ_PARAM_ID];
        $url = (string) $request->getUri()->withQuery(http_build_query([GPlayApps::REQ_PARAM_ID => $developerId]));

        $scriptDataInfo = $this->getScriptDataInfo($request, $response);

        $name = $scriptDataInfo[0][0][0];

        $cover = empty($scriptDataInfo[0][9][0][3][2]) ?
            null :
            new GoogleImage($scriptDataInfo[0][9][0][3][2]);
        $icon = empty($scriptDataInfo[0][9][1][3][2]) ?
            null :
            new GoogleImage($scriptDataInfo[0][9][1][3][2]);
        $developerSite = $scriptDataInfo[0][9][2][0][5][2] ?? null;
        $description = $scriptDataInfo[0][10][1][1] ?? '';

        return new Developer(
            Developer::newBuilder()
                ->setId($developerId)
                ->setUrl($url)
                ->setName($name)
                ->setDescription($description)
                ->setWebsite($developerSite)
                ->setIcon($icon)
                ->setCover($cover)
        );
    }

    /**
     * @param RequestInterface  $request
     * @param ResponseInterface $response
     *
     * @throws GooglePlayException
     *
     * @return array
     */
    private function getScriptDataInfo(RequestInterface $request, ResponseInterface $response): array
    {
        $scriptData = ScraperUtil::extractScriptData($response->getBody()->getContents());

        $scriptDataInfo = null;

        foreach ($scriptData as $key => $scriptValue) {
            if (isset($scriptValue[0][21])) {
                $scriptDataInfo = $scriptValue; // ds:5
                break;
            }
        }

        if ($scriptDataInfo === null) {
            throw (new GooglePlayException(
                sprintf(
                    'Error parse vendor page %s. Need update library.',
                    $request->getUri()
                )
            ))->setUrl($request->getUri()->__toString());
        }

        return $scriptDataInfo;
    }
}
