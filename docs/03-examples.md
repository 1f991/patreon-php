1. [Getting Started](01-getting-started.md)
2. [Obtaining OAuth Tokens For Patrons](02-oauth.md)
3. Patreon Integration Examples
4. [Method Documentation](04-documentation.md)
5. [Library Architecture](05-architecture.md)

# Examples

A number of example integrations are available which you can find below.

- [Display Patron's pictures](#display-patrons)
- [Create a list of your Most Valuable Patrons Email Addresses](#most-valuable-patrons)
- Check if a Patreon user visiting your website is a Patron of your Campaign
- [Delete a User's account when their Pledge is cancelled](#delete-users-when-pledge-is-cancelled)

## Display Patrons

Scenario: You're a Campaign Creator with a website on which you'd like to
display profile photos of your Patrons.

```php
require_once 'vendor/autoload.php';

use Squid\Patreon\Patreon;

$patreon = new Patreon('access_token');

$campaign = $patreon->campaigns()->getMyCampaignWithPledges();

$pledges = $campaign->pledges->each(function ($pledge) {
    echo '<div style="display: inline-block; padding: 10px;">
            <img src="' . $pledge->patron->thumb_url . '">
          </div>';
});
```

## Most Valuable Patrons

Scenario: You're a Campaign Creator and you'd like to generate a comma separated
list of Email Addresses for each active Patron who has contributed over $20
during the lifetime of their Pledge to the Campaign.

```php
require_once 'vendor/autoload.php';

use Squid\Patreon\Patreon;

$patreon = new Patreon('access_token');

$campaign = $patreon->campaigns()->getMyCampaignWithPledges();

$mvps = $campaign->pledges->filter(function ($pledge) {
    return $pledge->total_historical_amount_cents > 2000;
})->map(function ($list, $pledge) {
    return $pledge->patron->email;
})->implode(', ');
```

The variable `$mvps` is equal to a string containing the email address of
each Patron with a lifetime value of more than $20, e.g:

> jane@example.com, jack@example.com, john@example.com, joe@example.com

## Authorize Website Access For Patrons

Scenario: You have a section of your website that only people who have an active
pledge which is for more than $5 per month are able to access.

TODO: ...

## Delete Users When Pledge Is Cancelled

Scenario: You have a website which allows people to create an account when they
are a patron of your Patreon Campaign, and you need to delete the account if
their Pledge is cancelled.

```php
require_once 'vendor/autoload.php';

use Squid\Patreon\Patreon;

$patreon = new Patreon('access_token');

$pledges = $patreon->webhook()->accept(
    file_get_contents('php://input'),
    'my-secret',
    $_SERVER['HTTP_X_PATREON_SIGNATURE']
);

if ($_SERVER['HTTP_X_PATREON_EVENT'] === 'pledges:delete') {
    $pledges->each(function ($pledge) {
        User::where('email', $pledge->patron->email)->delete();
    });
}
```

# Next Steps

* Start developing!
* Get help?
* Learn about the architecture
