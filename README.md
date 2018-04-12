# Patreon PHP

A PHP library for interacting with the Patreon API.

# Documentation

* You must install an http client to use this library, recommended: guzzle.
* Run `composer style && composer test` to check code before contributing

# Examples

## Retrieving the Current User's information

```php
$patreon = new Patreon($accessToken);
$user = $patreon->me()->get();
echo "Hello {$user->full_name}";
```

## Retrieving all Pledges for the Current User's Campaign

```php
$patreon = new Patreon($accessToken);

$pledges = $patreon->pledges()->getCampaignPledges(
    $patreon->campaigns()->getMyCampaign()->id
);

$pledges->each(function ($pledge) {
    echo "{$pledge->patron->full_name} pledges {$pledge->amount_cents} cents.\n";
});
```

## Accept inbound webhook

```php
$patreon = new Patreon($accessToken);

$pledge = $patreon->webhook()->accept(
    file_get_contents('php://input'),
    $_SERVER['HTTP_X_PATREON_SIGNATURE'],
    $secret
);

echo $pledge->patron->full_name;
```
