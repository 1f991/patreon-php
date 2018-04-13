<?php declare(strict_types=1);

namespace Squid\Patreon\Entities;

class Card extends Entity
{
    /**
     * Type of payment method.
     * Example: "card"
     *
     * @var string
     */
    public $card_type;

    /**
     * Timestamp of when the card was added, ISO 8601 combined date and time in UTC.
     * Example: "2017-12-01T16:33:48+00:00"
     *
     * @var string
     */
    public $created_at;

    /**
     * Timestamp of when the card is due to expire, ISO 8601 combined date and
     * time in UTC. Appears to always be the first day of the month with 0 seconds.
     * Example: "2020-12-01T00:00:00+00:00"
     *
     * @var string
     */
    public $expiration_date;

    /**
     * Has a payment attempt to this card failed?
     * Maybe: this may refer to has a payment failed *ever* or did the last payment
     * attempt fail. Clarification required.
     *
     * @var bool
     */
    public $has_a_failed_payment;

    /**
     * Unknown.
     * Probably: if the card has been verified with an authorization charge.
     *
     * @var bool
     */
    public $is_verified;

    /**
     * Last 4 digits of the card.
     * Example: 1234
     *
     * @var string
     */
    public $number;

    /**
     * Stripe payment token.
     *
     * @var string
     */
    public $payment_token;

    /**
     * Unknown.
     * Probably: an ID referencing the payment token for payment log auditing?
     *
     * @var integer
     */
    public $payment_token_id;
}
