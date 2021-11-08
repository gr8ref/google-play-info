<?php

declare(strict_types=1);

/**
 * @author   gr8ref
 * @license  MIT
 *
 * @see      https://github.com/gr8ref/google-play-info
 */

namespace gr8ref\GPlay\Scraper\Extractor;

use gr8ref\GPlay\Model\AppId;
use gr8ref\GPlay\Model\GoogleImage;
use gr8ref\GPlay\Model\ReplyReview;
use gr8ref\GPlay\Model\Review;
use gr8ref\GPlay\Util\DateStringFormatter;

/**
 * @internal
 */
class ReviewsExtractor
{
    /**
     * @param AppId $requestApp
     * @param array $data
     *
     * @return array
     */
    public static function extractReviews(AppId $requestApp, array $data): array
    {
        $reviews = [];

        foreach ($data as $reviewData) {
            $reviews[] = self::extractReview($requestApp, $reviewData);
        }

        return $reviews;
    }

    /**
     * @param AppId $requestApp
     * @param       $reviewData
     *
     * @return Review
     */
    public static function extractReview(AppId $requestApp, $reviewData): Review
    {
        $reviewId = $reviewData[0];
        $reviewUrl = $requestApp->getUrl() . '&reviewId=' . urlencode($reviewId);
        $userName = $reviewData[1][0];
        $avatar = (new GoogleImage($reviewData[1][1][3][2]))->setSize(64);
        $date = DateStringFormatter::unixTimeToDateTime($reviewData[5][0]);
        $score = $reviewData[2] ?? 0;
        $text = (string) ($reviewData[4] ?? '');
        $likeCount = $reviewData[6];

        $reply = self::extractReplyReview($reviewData);

        return new Review(
            $reviewId,
            $reviewUrl,
            $userName,
            $text,
            $avatar,
            $date,
            $score,
            $likeCount,
            $reply
        );
    }

    /**
     * @param array $reviewData
     *
     * @return ReplyReview|null
     */
    private static function extractReplyReview(array $reviewData): ?ReplyReview
    {
        if (isset($reviewData[7][1])) {
            $replyText = $reviewData[7][1];
            $replyDate = DateStringFormatter::unixTimeToDateTime($reviewData[7][2][0]);

            if ($replyText && $reviewData) {
                return new ReplyReview(
                    $replyDate,
                    $replyText
                );
            }
        }

        return null;
    }
}
