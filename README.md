# Patreon PHP

A PHP library for interacting with the Patreon API.

# Documentation

* You must install an http client to use this library, recommended: guzzle.
* Run `composer style && composer test` to check code before contributing

# Examples

## Retrieving the current user's information

```php
$patreon = new Patreon($accessToken);
$user = $patreon->me()->get();
echo "Hello {$user->full_name}";
```
