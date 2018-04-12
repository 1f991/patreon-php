> :warning: This library is currently pre-release, explore with caution. Release
(including full documentation) is expected in the coming days.

# Patreon PHP

A PHP library for interacting with the
[Patreon Platform](https://www.patreon.com/portal), designed to provide the
easiest path for integrating Patreon into your application by abstracting away
the underlying Patreon API behind an interface of easy to use methods.

# Requirements

* PHP >=7.2
* [Composer](https://getcomposer.org/)
* An [HTTPlug compatible client](http://docs.php-http.org/en/latest/clients.html)
  such as [`php-http/guzzle6-adapter`](https://packagist.org/packages/php-http/guzzle6-adapter)

# Installation

Patreon PHP is available on
[Packagist](https://packagist.org/packages/squid/patreon).

```bash
composer require squid/patreon php-http/guzzle6-adapter
```

You may swap out [`php-http/guzzle6-adapter`](https://packagist.org/packages/php-http/guzzle6-adapter)
for your own
[HTTPlug Compatible Client](http://docs.php-http.org/en/latest/clients.html),
such as the [cURL client](https://packagist.org/packages/php-http/curl-client)
or the [React HTTP Adapter](https://packagist.org/packages/php-http/react-adapter).

# Getting Started

You will need a valid Patreon Access Token to use the Patreon PHP client.
Patreon provides Creator Access Tokens and standard OAuth Access Tokens. A
Creator Access Token can be used to access data about a single creators campaign
without the need to go through an OAuth flow, whereas OAuth Access Tokens can be
used to fetch data dynamically about Patrons when they log in to your
application.

1. Visit the Patreon [Clients & API Keys](https://www.patreon.com/portal/registration/register-clients) page and
click "Create Client"
2. Fill out the form and submit
3. Copy your `Creator's Access Token`

```php
require_once 'vendor/autoload.php';

$patreon = new Patreon('access_token');
$campaign = $patreon->campaigns()->getMyCampaign();

echo "Hello, {$campaign->creator->full_name}.\n";
echo "You're creating {$campaign->creation_name} for {$campaign->patron_count} patrons.\n";
```

# Examples

A number of example integrations are available which you can find below.

- Generate a list of Patrons and their email addresses
- Generate a list of Patrons who have contributed more than $50 to your Campaign
- Check if a Patreon user visiting your website is a Patron of your Campaign
- Receive a Webhook from Patreon when a Patron updates their Pledge

# Resources, Entities and Collections

Entities are objects with properties that are populated from Patreon responses.
Each Entity has defined properties which you can depend on existing.
Additionally, Entities expose helper methods that allow your application not to
concern itself with making determinations about Entity states, e.g: `$reward->isAvailableToChoose()` and `$pledge->hasMadeAPayment()`.

Resources represent Patreon API endpoints. Each Resource exposes methods for
fetching data from these endpoints. Each Resource can be accessed through the
`Patreon` client, e.g: `$patreon->campaigns()->getMyCampaign()`.

- `campaigns()` provides `getMyCampaign`, `getMyCampaignWithPledges`,
  `getCampaign` and `getCampaignWithPledges`
- `me()` provides `get`
- `pledges()` provides `getCampaignPledges` and `getPageOfCampaignPledges`

Additionally, the `Webhook` resource provides an `accept` method which will
verify the signature of a Webhook received from Patreon and return the Pledge
Entity.

## Collections

When there is more than one Entity a
[Collection](https://laravel.com/docs/5.6/collections) is given, Collections
"[...] provide a fluent, convenient wrapper for working with arrays of data". This
makes it very easy to work with groups of Entities and extract, transform or
manage their properties. For example:

```php
$patreon = new Patreon('access_token');
$campaign = $patreon->campaigns()->getMyCampaignWithPledges();

// echo a list of each patron's full_name
$campaign->pledges->each(function ($pledge) {
    echo "{$pledge->patron->full_name}\n";
});

// extract each patron's email address
$emails = $campaign->pledges->map(function ($pledge) {
    return $pledge->patron->email;
});
```

As a general pointer, when working this library you should never need to use
`while` or `foreach` loops.

# Getting Help

Please visit the [Patreon Developers forum](https://www.patreondevelopers.com/)
with any questions you have about using this library, or the Patreon Plaform.
Please report any bugs found in this library by creating a new [issue](https://github.com/1f991/patreon-php/issues).
A reproduceable test case should be included where possible, otherwise a
description of your problem and steps to reproduce would be very helpful.
