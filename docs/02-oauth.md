1. [Getting Started](01-getting-started.md)
2. Obtaining OAuth Tokens For Patrons
3. [Patreon Integration Examples](03-examples.md)
4. [Method Documentation](04-documentation.md)
5. [Library Architecture](05-architecture.md)

# Logging In With OAuth

Patreon implements the standard [OAuth 2.0](https://oauth.net/2/) login flow
which is compatible with any standards compliant OAuth 2.0 Client, including
The League of Extraordinary Packages
[OAuth 2.0 Client](http://oauth2-client.thephpleague.com/) and [Laravel Socialite](https://laravel.com/docs/5.6/socialite).

OAuth 2.0 Patreon PHP includes a simple Patreon OAuth Client which you can use
to accept user logins.

1. A user visits your login page
2. You redirect the user to the Patreon login page
3. The user is asked the user to confirm that they authorise your application
4. Patreon redirects the user back to your `redirect_uri` with a `code` query
  parameter
5. You make a request to Patreon exchanging the `code` for an `access_token` and
  `refresh_token`

After you have obtained the access token you are able to communicate with the
Patreon API on behalf of the user. You should store the user's access token and
refresh token in your database.

```php
require_once 'vendor/autoload.php';

use Squid\Patreon\OAuth;
use Squid\Patreon\Patreon;

$oauth = new OAuth(
    'client_id',
    'client_secret',
    'redirect_uri'
);

if (! isset($_GET['code'])) {
    Header('Location: ' . $oauth2->getAuthorizationUrl());
    exit;
}

$tokens = $oauth->getAccessToken($_GET['code']);

$patreon = new Patreon($tokens['access_token']);

$user = $patreon->me()->get();

echo "Hello, {$user->full_name}!";
if ($user->hasActivePledge()) {
    echo "You are a patron of my campaign, thank you!";
} else {
    echo "You are not a patron of my campaign.";
}
```
