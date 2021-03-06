<?php

/** @noinspection PhpUnusedPrivateFieldInspection */
declare(strict_types=1);

/**
 * @author   gr8ref
 * @license  MIT
 *
 * @see      https://github.com/gr8ref/google-play-info
 */

namespace gr8ref\GPlay\Enum;

use gr8ref\Enum;

/**
 * Contains all valid values for the "sort" parameter.
 *
 * @method static SortEnum HELPFULNESS() Returns the value of the sorting of reviews by helpfulness.
 * @method static SortEnum NEWEST()      Returns the value of the sorting of reviews by newest.
 * @method static SortEnum RATING()      Returns the value of the sorting of reviews by rating.
 */
class SortEnum extends Enum
{
    /** @var int Most helpful first */
    private const HELPFULNESS = 1;

    /** @var int Newest */
    private const NEWEST = 2;

    /** @var int Rating */
    private const RATING = 3;
}
