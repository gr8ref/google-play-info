<?php

declare(strict_types=1);

/**
 * @author   Ne-Lexa
 * @license  MIT
 *
 * @see      https://github.com/Ne-Lexa/google-play-info
 */

namespace rumi55\GPlay\Scraper;

use rumi55\GPlay\Exception\GooglePlayException;
use rumi55\GPlay\Model\AppId;
use rumi55\GPlay\Model\Review;
use rumi55\GPlay\Scraper\Extractor\ReviewsExtractor;
use rumi55\GPlay\Util\ScraperUtil;
use rumi55\HttpClient\ResponseHandlerInterface;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;
use function GuzzleHttp\Psr7\parse_query;

/**
 * @internal
 */
class AppSpecificReviewScraper implements ResponseHandlerInterface
{
    /** @var AppId */
    private $requestApp;

    /**
     * OneAppReviewScraper constructor.
     *
     * @param AppId $requestApp
     */
    public function __construct(AppId $requestApp)
    {
        $this->requestApp = $requestApp;
    }

    /**
     * @param RequestInterface  $request
     * @param ResponseInterface $response
     *
     * @throws GooglePlayException
     *
     * @return Review
     */
    public function __invoke(RequestInterface $request, ResponseInterface $response): Review
    {
        $reviewId = parse_query($request->getUri()->getQuery())['reviewId'];
        $scriptData = ScraperUtil::extractScriptData($response->getBody()->getContents());

        foreach ($scriptData as $key => $value) {
            if (isset($value[0][0][0]) && $value[0][0][0] === $reviewId) {
                return ReviewsExtractor::extractReview($this->requestApp, $value[0][0]);
            }
        }

        throw new GooglePlayException(
            sprintf('%s application review %s does not exist.', $this->requestApp->getId(), $reviewId)
        );
    }
}
