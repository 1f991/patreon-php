1. [Getting Started](01-getting-started.md)
2. [Obtaining OAuth Tokens For Patrons](02-oauth.md)
3. [Patreon Integration Examples](03-examples.md)
4. [Method Documentation](04-documentation.md)
5. Library Architecture

# Resources, Entities and Collections

This library is designed to be easy to work with and easy to understand if you
wish to learn how it works under the good. Take a look at the [src](src)
directory to see the Entities and Resources that make up the library, and check
out the [tests](tests) to see examples of how the various methods can be used.

## Entities

Entities are objects with properties that are populated from Patreon responses.
Each Entity has defined properties which you can depend on existing.
Additionally, Entities expose helper methods that allow your application not to
concern itself with making determinations about Entity states, e.g: `$reward->isAvailableToChoose()` and `$pledge->hasMadeAPayment()`.

You can browse the properties of each Entity via their class files:

* [Address](src/Patreon/Entities/Address.php)
* [Campaign](src/Patreon/Entities/Campaign.php)
* [Goal](src/Patreon/Entities/Goal.php)
* [Pledge](src/Patreon/Entities/Pledge.php)
* [Reward](src/Patreon/Entities/Reward.php)
* [User](src/Patreon/Entities/User.php)

## Resources

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

# Next Steps

* Contribute?
* Get help?
