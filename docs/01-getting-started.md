1. Getting Started
2. [Obtaining OAuth Tokens For Patrons](02-oauth.md)
3. [Patreon Integration Examples](03-examples.md)
4. [Method Documentation](04-documentation.md)
5. [Library Architecture](05-architecture.md)

# Getting Started

You will need a valid Patreon Access Token to use the Patreon PHP client.
Patreon provides two types of access token, Creator Access Token and OAuth
Access Token.

## Creator Access Token

A Creator Access Token grants API Access to the Creator's account details
and their Campaign which includes Pledges. This token is generated when the
Patreon Creator creates an API Client and does not require implementing an OAuth
flow.

1. Visit the Patreon [Clients & API Keys](https://www.patreon.com/portal/registration/register-clients) page and
click "Create Client"
2. Fill out the form and submit
3. Copy the `Creator's Access Token`

## OAuth Access Token

An OAuth Access Token grants API access to the User's account details, and their
relationship to the Campaign of the Client's Creator. This token is obtained
through the standard OAuth code grant flow.

1. Visit the Patreon [Clients & API Keys](https://www.patreon.com/portal/registration/register-clients) page and
click "Create Client"
2. Fill out the form and submit
3. Copy the `Client ID` and `Client Secret`
4. [Configure your OAuth client](https://docs.patreon.com/#oauth)

# Using Patreon PHP

You will first need to install an
[HTTPlug Compatible Client](http://docs.php-http.org/en/latest/clients.html)
such as `php-http/guzzle6-adapter` alongside the library.

```bash
composer require squid/patreon php-http/guzzle6-adapter
```

The Client is instantiated with an Access Token (either type!) and from there
you have access to the Resources. Each Resource exposes methods which you call
to obtain data from the API.

```php
require_once 'vendor/autoload.php';

$patreon = new Patreon('access_token');
$campaign = $patreon->campaigns()->getMyCampaign();

echo "Hello, {$campaign->creator->full_name}.\n";
echo "You're creating {$campaign->creation_name} for {$campaign->patron_count} patrons.\n";
```

## Resources and Methods

Resources are available as methods on the Client. Each Resource exposes methods
that return either an individual Entity or a Collection of Entities.

### Me

The [`me()` Resource](src/Patreon/Resources/CurrentUser.php) represents the
`current_user` endpoint. This Resource provides a `get()` method which will
return a [`User` Entity](src/Patreon/Entities/User.php). Additionally, if the
User is a Patron of the Client Creator's Campaign then their Pledge to that
Campaign will be included.

```php
$patreon = new Patreon('access_token');
$user = $patreon->me()->get();

echo "Hello, {$user->full_name}.\n";

if ($user->pledge->isPledgeActive()) {
    echo "You pledge {$user->pledge->amount_cents} cents per {$user->pledge->campaign->pay_per_name}.\n";
    echo "Your reward is {$user->pledge->reward->title}\n";
}
```

You would use `me()` to:

* Retrieve data about the user who logged in through your OAuth flow
* Determine if the user should have access to your website

### Campaigns

The [`campaigns()` Resource](src/Patreon/Resources/Campaigns.php) represents the
`campaigns` endpoint. This Resource provides `getMyCampaign()`,
`getMyCampaignWithPledges()`, `getCampaign(int $id)` and
`getCampaignWithPledges(int $id)`, all of which return a
[`Campaign` Entity](src/Patreon/Entities/Campaign.php). Pledges, when requested,
are included as a Collection on the `pledges` property.

```php
$patreon = new Patreon('access_token');
$campaign = $patreon->campaigns()->getMyCampaignWithPledges();

echo "Hello, {$campaign->creator->full_name}. You have {$campaign->patron_count} patrons.\n";

$campaign->pledges->each(function ($pledge) {
    echo "{$pledge->patron->full_name} pledge's {$pledge->amount_cents} cents per {$pledge->campaign->pay_per_name}.\n";
    echo "Their reward is {$pledge->reward->title}\n";
});
```

:sparkles: Patreon PHP handles pagination behind the scenes, a single call to a
`...withPledges` method will retrieve all Pledges for the campaign, whether
there's 1 pledge or 10,000.

:warning: Due to the potential for a campaign to have thousands of pledges, you
should avoid retrieving pledges in response to an HTTP request, instead use a
background process to prevent timing out.

:closed_lock_with_key: Patreon must grant your client permission to access other
User's Campaigns, you can only access the Campaign of the Client's Creator by
default. You will probably not need the `...ById` methods.

You would use `campaigns()` to:

* Retrieve data about your Creator's Campaign
* Access all of the Pledges to your Creator's Campaign

### Pledges

The [`pledges()` Resource](src/Patreon/Resources/Pledges.php) represents the
`campaigns/{id}/pledges` endpoint. This Resource provides
`getCampaignPledges(int $id)` and `getPageOfCampaignPledges(...)` which both
return a Collection of Pledges.

```php
$patreon = new Patreon('access_token');
$pledges = $patreon->pledges()->getCampaignPledges($id);

$pledges->each(function ($pledge) {
    echo "{$pledge->patron->full_name} pledge's {$pledge->amount_cents} cents per {$pledge->campaign->pay_per_name}.\n";
    echo "Their reward is {$pledge->reward->title}\n";
});
```

Should you need to access a single page of Pledges directly, you can make use of
the `getPageOfCampaignPledges` method. Pagination is cursor-based, with sorting
by `created`, `-created`, `updated` and `-updated`. You can learn more about
pagination in the [Platform Documentation — "Pagination and sorting"](https://docs.patreon.com/#pagination-and-sorting).

```php
$patreon = new Patreon('access_token');

$pledges = $patreon->pledges()->getPageOfCampaignPledges(
    1,                          // id
    10,                         // count
    ['updated'],                // sort
    '2017-12-01T16:33:48+00:00' // cursor
);
```

You would use `pledges()` to:

* Access all of the Pledges to your Creator's Campaign as a Collection without
  Campaign data.

### Webhook

The [`webhook()` Resource](src/Patreon/Resources/Webhook.php) handles inbound
webhooks from Patreon. This Resource provides
`accept($body, $secret, $signature)` which verifies the signature and then
returns a Collection of Entities received from Patreon, and
`verifySignature(...)` verifies the signature in the request is valid.

```php
$patreon = new Patreon('access_token');

$pledges = $patreon->webhook()->accept(
    file_get_contents('php://input'),    // Request body
    $secret,                             // Webhook secret
    $_SERVER['HTTP_X_PATREON_SIGNATURE'] // Request signature
);

if ($_SERVER['HTTP_X_PATREON_EVENT'] === 'pledges:delete') {
    $pledges->each(function ($pledge) {
        User::where('email', $pledge->patron->email)->delete();
    });
}
```

You can learn more about Patreon Webhooks in the [Platform Documentation — "Webhooks"](https://docs.patreon.com/#webhooks).

## Next Steps

Hopefully you're now familiar with the way that the library works and the
possibilities available, [check out some examples](03-examples.md) to see how
you could achieve common tasks.
