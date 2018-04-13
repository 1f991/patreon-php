<?php declare(strict_types=1);

namespace Squid\Patreon\Entities;

class User extends Entity
{
    /**
     * "About Me" text.
     * Example: "Hello World!"
     *
     * @var string
     */
    public $about;

    /**
     * Timestamp of the account creation, ISO 8601 combined date and time in UTC.
     * Example: 2017-12-01T16:33:48+00:00
     *
     * @var string
     */
    public $created;

    /**
     * ID of the Discord account connected for role rewards.
     * Example: "100000000000000000"
     *
     * @var string
     */
    public $discord_id;

    /**
     * Email Address.
     * Example: user@example.com
     *
     * @var string
     */
    public $email;

    /**
     * Facebook profile URL.
     * Example: "https://facebook.com/username"
     *
     * @var string
     */
    public $facebook;

    /**
     * ID of the Facebook account connected.
     * Example: "100000000000000"
     *
     * @var string
     */
    public $facebook_id;

    /**
     * First Name.
     * Example: "John"
     *
     * @var string
     */
    public $first_name;

    /**
     * Full Name ("first_name last_name")
     * Example: "John Doe"
     *
     * @var string
     */
    public $full_name;

    /**
     * Unknown.
     * Maybe: a legacy value representing the users gender (0 or 1)?
     *
     * @var integer
     */
    public $gender;

    /**
     * Does the user have a password? False if they signed up using Facebook.
     *
     * @var bool
     */
    public $has_password;

    /**
     * CDN URL to profile photo.
     * Example: https://c8.patreon.com/2/400/1
     *
     * @var string
     */
    public $image_url;

    /**
     * Is the account deleted?
     *
     * @var bool
     */
    public $is_deleted;

    /**
     * Is the users email address verified?
     *
     * @var bool
     */
    public $is_emailed_verified;

    /**
     * Unknown.
     *
     * @var bool
     */
    public $is_nuked;

    /**
     * Unknown.
     *
     * @var bool
     */
    public $is_suspended;

    /**
     * Last Name.
     * Example: "Doe"
     *
     * @var string
     */
    public $last_name;

    /**
     * Social connections and their associated data, e.g: scopes, ids.
     * Example:
     * ['discord' => [
     *    'scopes' => ['guilds', 'guilds.join', 'identify'],
     *    'user_id' => '100000000000000000'
     * ]];
     *
     * @var array
     */
    public $social_connections;

    /**
     * CDN URL to thumbnail (100 x 100) of profile photo.
     * Example: https://c8.patreon.com/2/100/1
     *
     * @var string
     */
    public $thumb_url;

    /**
     * Twitch.tv profile URL.
     * Example: https://twitch.tv/username
     *
     * @var string
     */
    public $twitch;

    /**
     * Twitter username.
     * Example: username
     *
     * @var string
     */
    public $twitter;

    /**
     * URL to Patreon profile.
     * Example: https://patreon.com/username
     *
     * @var string
     */
    public $url;

    /**
     * Unique username for profile url.
     * Example: username
     *
     * @var string
     */
    public $vanity;

    /**
     * YouTube channel URL.
     * Example: https://youtube.com/username
     *
     * @var string
     */
    public $youtube;

    /**
     * Relations that should be initialized as empty Collections.
     *
     * @var array
     */
    protected $relations = [
        'pledges',
    ];
}
