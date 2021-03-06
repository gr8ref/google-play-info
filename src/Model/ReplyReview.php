<?php

declare(strict_types=1);

/**
 * @author   gr8ref
 * @license  MIT
 *
 * @see      https://github.com/gr8ref/google-play-info
 */

namespace gr8ref\GPlay\Model;

use gr8ref\GPlay\GPlayApps;

/**
 * Contains the developer’s reply to a review in the Google Play store.
 *
 * @see Review Contains review of application on Google Play store.
 * @see GPlayApps::getReviews() Returns reviews of the
 *     Android app in the Google Play store.
 */
class ReplyReview implements \JsonSerializable
{
    use JsonSerializableTrait;

    /** @var \DateTimeInterface Reply date. */
    private $date;

    /** @var string Reply text. */
    private $text;

    /**
     * Creates an object with information about the developer’s response
     * to a review of an application in the Google Play store.
     *
     * @param \DateTimeInterface $date reply date
     * @param string             $text reply text
     */
    public function __construct(\DateTimeInterface $date, string $text)
    {
        $this->date = $date;
        $this->text = $text;
    }

    /**
     * Returns reply date.
     *
     * @return \DateTimeInterface reply date
     */
    public function getDate(): \DateTimeInterface
    {
        return $this->date;
    }

    /**
     * Returns reply text.
     *
     * @return string reply text
     */
    public function getText(): string
    {
        return $this->text;
    }

    /**
     * Returns class properties as an array.
     *
     * @return array class properties as an array
     */
    public function asArray(): array
    {
        return [
            'date' => $this->date->format(\DateTimeInterface::RFC3339),
            'timestamp' => $this->date->getTimestamp(),
            'text' => $this->text,
        ];
    }
}
