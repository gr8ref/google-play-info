<?php

declare(strict_types=1);

/**
 * @author   gr8ref
 * @license  MIT
 *
 * @see      https://github.com/gr8ref/google-play-info
 */

namespace gr8ref\GPlay\Scraper;

use gr8ref\HttpClient\ResponseHandlerInterface;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;

/**
 * @internal
 */
class ExistsAppScraper implements ResponseHandlerInterface
{
    /**
     * @param RequestInterface  $request
     * @param ResponseInterface $response
     *
     * @return mixed
     */
    public function __invoke(RequestInterface $request, ResponseInterface $response)
    {
        return $response->getStatusCode() < 300;
    }
}
