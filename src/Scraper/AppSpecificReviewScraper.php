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
use gr8ref\GPlay\Model\AppId;
use gr8ref\GPlay\Model\Review;
use gr8ref\GPlay\Scraper\Extractor\ReviewsExtractor;
use gr8ref\GPlay\Util\ScraperUtil;
use gr8ref\HttpClient\ResponseHandlerInterface;
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
