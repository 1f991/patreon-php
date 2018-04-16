<?php declare(strict_types=1);

namespace Squid\Patreon\Entities;

class Reward extends Entity
{
    /**
     * Amount in cents required to be eligible for this reward.
     * Notes: This is legacy, do not use this. Use `amount_cents`.
     * Source: https://www.patreondevelopers.com/t/f/197/2
     *
     * @var integer
     */
    public $amount;

    /**
     * Amount in cents required to be eligible for this reward.
     *
     * @var integer
     */
    public $amount_cents;

    /**
     * Campaign that the Reward belongs to.
     *
     * @var \Squid\Patreon\Entities\Campaign
     */
    public $campaign;

    /**
     * Timestamp of the reward creation, ISO 8601 combined date and time in UTC.
     * Example: "2017-12-01T16:33:48+00:00"
     *
     * @var string
     */
    public $created_at;

    /**
     * Description of the reward, rendered as HTML.
     * Example: "Access to my Patron only streams."
     *
     * @var string
     */
    public $description;

    /**
     * Discord Roles granted when the patron has Discord attached to their Patreon
     * account.
     *
     * @var array
     */
    public $discord_role_ids;

    /**
     * Timestamp of the last edit to the reward, ISO 8601 combined date and time
     * in UTC.
     * Example: "2017-12-01T16:33:48+00:00"
     *
     * @var string
     */
    public $edited_at;

    /**
     * Unknown.
     *
     * @var string
     */
    public $image_url;

    /**
     * Number of Pledges with this Reward.
     * Note: all pledges, active and inactive, are included in this count.
     *
     * @var integer
     */
    public $patron_count;

    /**
     * Number of posts with this Reward selected as the lowest reward tier that
     * can access the post.
     * Note: Patrons can access posts from their Reward tier and any lower Reward
     * tier so to calculate the total number of posts the Patron can access you
     * would need to include the post_count for this reward and each lower Reward.
     *
     * @var integer
     */
    public $post_count;

    /**
     * Is this Reward visible on the campaign?
     *
     * @var bool
     */
    public $published;

    /**
     * Timestamp of when the reward was published, ISO 8601 combined date and time
     * in UTC.
     * Example: "2017-12-01T16:33:48+00:00"
     *
     * @var string
     */
    public $published_at;

    /**
     * The number of remaining rewards available if there is a user_limit.
     *
     * @var integer
     */
    public $remaining;

    /**
     * Does the reward require a shipping address?
     *
     * @var bool
     */
    public $requires_shipping;

    /**
     * Title of the Reward.
     * Example: "Exclusive Live Chat"
     */
    public $title;

    /**
     * Timestamp of when the reward was unpublished, ISO 8601 combined date and time
     * in UTC.
     * Example: "2017-12-01T16:33:48+00:00"
     *
     * @var string
     */
    public $unpublished_at;

    /**
     * Relative URL for selecting this reward when becoming a patron.
     * Example: /bePatron?c=1&rid=1
     *
     * @var string
     */
    public $url;

    /**
     * Maximum number of pledges that can have this reward. This includes paused
     * and declined pledges.
     * Source: https://www.patreondevelopers.com/t/f/131/3
     *
     * @var integer
     */
    public $user_limit;

    /**
     * Is this Reward available for Patrons to select?
     *
     * @return bool
     */
    public function isAvailableToChoose(): bool
    {
        return ! $this->isSystemReward() && $this->hasRemainingLimit();
    }

    /**
     * Does the Reward have spaces left, allowing it to be picked by a patron.
     *
     * @return bool
     */
    public function hasRemainingLimit(): bool
    {
        return $this->remaining !== 0;
    }

    /**
     * Is this Reward a Reward used by the Patreon system, and not configured by
     * the campaign creator?
     * Note: Rewards ID -1 "Everyone" and 0 "Patrons Only" appear to be used by
     * the system for permissions to access posts. You should exclude system
     * rewards when listing rewards.
     *
     * @return bool
     */
    public function isSystemReward(): bool
    {
        return (int) $this->id < 1;
    }

    /**
     * A system Reward should not be attached to a Campaign during hydration
     * because they are not creator-configured rewards, they're rewards used
     * internally by Patreon (that probably should not be exposed via the API).
     *
     * @return bool
     */
    public function shouldAttach(): bool
    {
        return $this->isSystemReward();
    }
}
