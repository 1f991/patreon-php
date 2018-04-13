<?php declare(strict_types=1);

namespace Squid\Patreon\Entities;

class Pledge extends Entity
{
    /**
     * Address associated with the Pledge if Reward `requires_shipping`.
     *
     * @var \Squid\Patreon\Entities\Address
     */
    public $address;

    /**
     * Amount paid by the patron when billed.
     *
     * @var integer
     */
    public $amount_cents;

    /**
     * Card associated with the Pledge for payment.
     *
     * @var \Squid\Patreon\Entities\Card
     */
    public $card;

    /**
     * Timestamp of the pledge creation, ISO 8601 combined date and time in UTC.
     * Example: "2017-12-01T16:33:48+00:00"
     *
     * @var string
     */
    public $created_at;

    /**
     * Creator (Campaign owner) that the Pledge is to.
     *
     * @var \Squid\Patreon\Entities\User
     */
    public $creator;

    /**
     * Timestamp of the most recent failed payment, ISO 8601 combined date and
     * time in UTC. Example: "2017-12-01T16:33:48+00:00"
     * Notes: "only ever treat this as a boolean value (is it null, or is it
     * not null, since while the date is useful to us internally for retry
     * purposes, it’s not super useful outside)"
     * Source: https://www.patreondevelopers.com/t/125/8
     *
     * @var string
     */
    public $declined_since;

    /**
     * Does the patron have a shipping address associated with this pledge?
     *
     * @var bool
     */
    public $has_shipping_address;

    /**
     * Has the patron paused their pledge?
     * Notes: A pledge can only be paused if the campaign is per-month, a
     * per-post campaign cannot have any paused pledges.
     *
     * @var bool
     */
    public $is_paused;

    /**
     * Patron that created the Pledge.
     *
     * @var \Squid\Patreon\Entities\User
     */
    public $patron;

    /**
     * Legacy / unused.
     * Source: https://www.patreondevelopers.com/t/131/3
     *
     * @var bool
     */
    public $patron_pays_fees;

    /**
     * Maximum amount the patron can be charged per-month on per-creation
     * campaigns.
     * Source: https://patreon.zendesk.com/hc/en-us/articles/115002984506
     *
     * @var integer
     */
    public $pledge_cap_cents;

    /**
     * Reward chosen by the Patron if they chose an option.
     *
     * @var \Squid\Patreon\Entities\Reward
     */
    public $reward;

    /**
     * Total amount that the patron has been billed through the life of the pledge.
     * Notes: this value will be greater than 0 once the patron has been
     * successfully billed.
     * Source: https://www.patreondevelopers.com/t/125/8
     *
     * @var integer
     */
    public $total_historical_amount_cents;

    /**
     * An active pledge is not paused and is not currently declined.
     * Note: a pledge can be active even if the patron hasn't been charged yet
     * if the campaign does not charge upfront.
     *
     * @return bool
     */
    public function isPledgeActive(): bool
    {
        return $this->declined_since === null && $this->is_paused === false;
    }

    /**
     * Was the latest attempt to charge the patrons payment method declined?
     * Note: Patreon will retry payment methods and encourage the user to fix
     * the issue by adding a new payment method. When payment has repeatedly
     * failed for more than (undefined period of time) the pledge will be deleted.
     * Source: https://www.patreondevelopers.com/t/131/3
     *
     * @return bool
     */
    public function isPaymentDeclined(): bool
    {
        return $this->declined_since !== null;
    }

    /**
     * Has the patron ever made a successful payment?
     *
     * @return bool
     */
    public function hasMadeAPayment(): bool
    {
        return $this->total_historical_amount_cents > 0;
    }
}
