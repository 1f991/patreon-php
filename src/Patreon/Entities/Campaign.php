<?php declare(strict_types=1);

namespace Squid\Patreon\Entities;

use Tightenco\Collect\Support\Collection;

class Campaign extends Entity
{
    /**
     * Resource type (from Patreon).
     *
     * @var string
     */
    protected $type = 'campaign';

    /**
     * Timestamp of the campaign creation, ISO 8601 combined date and time in UTC.
     * Example: "2017-12-01T16:33:48+00:00"
     *
     * @var string
     */
    public $created_at;

    /**
     * Number of creations associated with the campaign.
     *
     * @var integer
     */
    public $creation_count;

    /**
     * Name of the creation, "x is creating..."
     * Example: "Music"
     *
     * @var string
     */
    public $creation_name;

    /**
     * Campaign owner.
     *
     * @var \Squid\Patreon\Entities\User
     */
    public $creator;

    /**
     * ID of the Discord server that roles are granted in.
     * Example: "100000000000000000"
     *
     * @var string
     */
    public $discord_server_id;

    /**
     * Unknown.
     *
     * @var bool
     */
    public $display_patron_goals;

    /**
     * Whether or not the campaigns earnings are visible.
     *
     * @var bool
     */
    public $earnings_visibility;

    /**
     * URL of CDN hosted small version of the Campaign header image.
     * Example: https://c10.patreonusercontent.com/[...]
     *
     * @var string
     */
    public $image_small_url;

    /**
     * URL of CDN hosted Campaign header image.
     * Example: https://c10.patreonusercontent.com/[...]
     *
     * @var string
     */
    public $image_url;

    /**
     * Does the campaign charge immediately when a new pledge is created
     * instead of waiting until the next billing cycle?
     *
     * @var bool
     */
    public $is_charge_upfront;

    /**
     * Unknown.
     * Maybe: a legacy version of is_charge_upfront; or is_charge_upfront is
     * the legacy field?
     *
     * @var bool
     */
    public $is_charged_immediately;

    /**
     * Unknown.
     * Maybe: a legacy version of is_charge_upfront; or is_charge_upfront is
     * the legacy field?
     *
     * @var bool
     */
    public $is_first_month_upfront;

    /**
     * Does the campaign bill every month, instead of per creation?
     *
     * @var bool
     */
    public $is_monthly;

    /**
     * Does the campaign have adult content?
     *
     * @var bool
     */
    public $is_nsfw;

    /**
     * Unknown.
     *
     * @var bool
     */
    public $is_plural;

    /**
     * Embed HTML for the main video.
     * Example: <iframe src="//www.youtube.com/embed/id"></iframe>
     *
     * @var string
     */
    public $main_video_embed;

    /**
     * URL for the main video.
     * Example: https://www.youtube.com/watch?v=aDiUGnJtjeA
     *
     * @var string
     */
    public $main_video_url;

    /**
     * Unknown.
     *
     * @var string
     */
    public $one_liner;

    /**
     * Legacy field to be removed in future.
     * Source: https://www.patreondevelopers.com/t/h/131/3
     *
     * @var integer
     */
    public $outstanding_payment_amount_cents;

    /**
     * Number of active patrons.
     *
     * @var integer
     */
    public $patron_count;

    /**
     * Name of what the user is paying per.
     * Examples: month, video
     *
     * @var
     */
    public $pay_per_name;

    /**
     * Sum of active pledge values in cents.
     *
     * @var integer
     */
    public $pledge_sum;

    /**
     * Path to the pledge page for this campaign.
     * Example: /bePatron?c=1
     *
     * @var string
     */
    public $pledge_url;

    /**
     * Timestamp of when campaign was first published, ISO 8601 combined date
     * and time in UTC.
     * Example: "2017-12-01T16:33:48+00:00"
     *
     * @var string
     */
    public $published_at;

    /**
     * HTML of the campaign summary as displayed on the campaign page.
     *
     * @var string
     */
    public $summary;

    /**
     * Embed HTML for the thanks video.
     * Example: <iframe src="//www.youtube.com/embed/id"></iframe>
     *
     * @var string
     */
    public $thanks_embed;

    /**
     * Thanks Message displayed to the patron after a pledge is created.
     * Example: "Thank you for becoming my patron."
     *
     * @var string
     */
    public $thanks_msg;

    /**
     * Video displayed along side the Thanks Message after a pledge is created.
     * Example: https://www.youtube.com/watch?v=aDiUGnJtjeA
     *
     * @var string
     */
    public $thanks_video_url;

    /**
     * Relations that should be initialized as empty Collections.
     *
     * @var array
     */
    protected $relations = [
        'goals',
        'pledges',
        'rewards',
    ];

    /**
     * Get an absolute URL to the pledge page for this Campaign.
     *
     * @return string
     */
    public function getPledgeUrl(): string
    {
        return self::PATREON_URL . $this->pledge_url;
    }

    /**
     * Get a collection of the Rewards that are available to choose.
     *
     * @return \Tightenco\Collect\Support\Collection
     */
    public function getAvailableRewards(): Collection
    {
        return $this->rewards->filter(
            function ($reward) {
                return $reward->isAvailableToChoose();
            }
        );
    }
}
