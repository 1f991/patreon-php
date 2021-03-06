# Patreon PHP

[![Packagist](https://img.shields.io/packagist/v/squid/patreon.svg)](https://packagist.org/packages/squid/patreon)
[![license](https://img.shields.io/github/license/1f991/patreon-php.svg)](/LICENSE)
[![CircleCI branch](https://img.shields.io/circleci/project/github/1f991/patreon-php/master.svg)](https://circleci.com/gh/1f991/patreon-php/tree/master)
[![Maintainability](https://api.codeclimate.com/v1/badges/e1a7ae0afedbdfbcf94b/maintainability)](https://codeclimate.com/github/1f991/patreon-php/maintainability)
[![Test Coverage](https://api.codeclimate.com/v1/badges/e1a7ae0afedbdfbcf94b/test_coverage)](https://codeclimate.com/github/1f991/patreon-php/test_coverage)

A PHP library for interacting with the
[Patreon Platform](https://www.patreon.com/portal), designed to provide the
easiest path for integrating Patreon into your application with a simple
interface that abstracts away the underlying Patreon API.

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

# How To Use

After you've installed the package you're ready to instantiate a client with
your access token and then request data for resources.

```php
use Squid\Patreon\Patreon;

$patreon = new Patreon('access_token');
$campaign = $patreon->campaigns()->getMyCampaignWithPledges();

echo "Hello, {$campaign->creator->full_name}! You have {$campaign->patron_count} patrons.\n";

$campaign->pledges->each(function ($pledge) {
    echo "{$pledge->patron->full_name} has been a patron since {$pledge->created_at}.\n";
});
```

# Learn More

Documentation is available covering all aspects of the library, from getting
started to architecture, or you can get started by looking at some examples of
how to achieve the most common integrations.

1. [Getting Started](docs/01-getting-started.md)
2. [Obtaining OAuth Tokens For Patrons](docs/02-oauth.md)
3. [Patreon Integration Examples](docs/03-examples.md)
3. [Method Documentation](docs/04-documentation.md)
4. [Library Architecture](docs/05-architecture.md)

## Examples

A full example project that you can download and use is available at [1f991/patreon-php-example](https://github.com/1f991/patreon-php-example) which
includes:

* Display a list of Patrons
* Instantly add/delete/update patrons using Webhooks
* Allow Patrons to log in with OAuth

A demo of the example project can be found live at [patreon.1f991.com](https://patreon.1f991.com)
where you can create a pledge and log in to see it in action.

Alternatively, smaller self-contained examples of specific implementations are
available in the documentation for this project:

- [Display Patron's pictures](/docs/03-examples.md#display-patrons)
- [Create a list of your Most Valuable Patrons Email Addresses](/docs/03-examples.md#most-valuable-patrons)
- [Delete a User's account when their Pledge is cancelled](/docs/03-examples.md#delete-users-when-pledge-is-cancelled)

# Getting Help

Please visit the [Patreon Developers forum](https://www.patreondevelopers.com/)
with any questions you have about using this library, or the Patreon Plaform.
Please report any bugs found in this library by creating a new [issue](https://github.com/1f991/patreon-php/issues).
A reproduceable test case should be included where possible, otherwise a
description of your problem and steps to reproduce would be very helpful.

# Dependencies

This library has been made possible by the fantastic open source contributions
of others, including...

- [Yang](https://github.com/woohoolabs/yang), a JSON:API Client Library by Woohoo Labs
- [HTTPlug](https://github.com/php-http/httplug), the HTTP client abstraction for PHP
- [Collect](https://github.com/tightenco/collect), a split of
  [Laravel Collections](https://laravel.com/docs/5.6/collections)

# Security Vulnerabilities

Please reach out to Samuel Ryan via email (<sryan@1f991.com>) to report any
security concerns you may have discovered with this library. Acknowledgement
will be provided within 24 hours.

# License

Patreon PHP is open-sourced software licensed under the [MIT license](https://choosealicense.com/licenses/mit/).
