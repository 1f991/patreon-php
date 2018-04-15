<?php declare(strict_types=1);

namespace Squid\Patreon;

use Http\Client\HttpClient;
use Squid\Patreon\Exceptions\OAuthScopesAreInvalid;
use Http\Discovery\HttpClientDiscovery;
use Http\Discovery\MessageFactoryDiscovery;

class OAuth
{
    /**
    * Valid OAuth scopes supported by Patreon.
    *
    * @var array
    */
    const OAUTH_SCOPES = [
        'users',
        'pledges-to-me',
        'my-campaign'
    ];

    /**
     * Constructs a new OAuth client.
     *
     * @param string     $clientId     Patreon Platform Client ID
     * @param string     $clientSecret Patreon Platform Client Secret
     * @param string     $redirectUri  Whitelisted Client Redirect URI
     * @param HttpClient $httpClient   Http Client
     *
     * @return void
     */
    public function __construct(
        string $clientId,
        string $clientSecret,
        string $redirectUri,
        ?HttpClient $httpClient = null
    ) {
        $this->clientId = $clientId;
        $this->clientSecret = $clientSecret;
        $this->redirectUri = $redirectUri;
        $this->httpClient = $httpClient ?: HttpClientDiscovery::find();
    }

    /**
     * Get an Authorization URL to redirect the user to.
     *
     * @param array $scope Array of valid scopes
     *
     * @return string
     */
    public function getAuthorizationUrl(
        array $scope = ['users', 'pledges-to-me', 'my-campaign']
    ): string {
        if ($invalid = array_diff($scope, self::OAUTH_SCOPES)) {
            throw OAuthScopesAreInvalid::scopes($invalid, self::OAUTH_SCOPES);
        }

        $query = http_build_query(
            [
            'response_type' => 'code',
            'client_id' => $this->clientId,
            'redirect_uri' => $this->redirectUri,
            'scope' => implode(' ', $scope),
            ]
        );

        return 'https://www.patreon.com/oauth2/authorize?' . $query;
    }

    /**
     * Get an Access Token from the user's OAuth code.
     *
     * @param string $code OAuth authorized_code
     *
     * @return array
     */
    public function getAccessToken(string $code): array
    {
        return $this->sendTokenRequest(
            [
            'code' => $code,
            'grant_type' => 'authorization_code',
            'redirect_uri' => $this->redirectUri,
            ]
        );
    }

    /**
     * Get a new Access Token using the user's Refresh Token.
     *
     * @param string $refreshToken User's Refresh Token
     *
     * @return array
     */
    public function getNewToken(string $refreshToken)
    {
        return $this->sendTokenRequest(
            [
            'grant_type' => 'refresh_token',
            'refresh_token' => $refreshToken,
            ]
        );
    }

    /**
     * Send a request to the Patreon OAuth token endpoint.
     *
     * @param array $parameters Parameters to include in the request.
     *
     * @return array
     */
    protected function sendTokenRequest(array $parameters): array
    {
        $parameters = array_merge(
            $parameters,
            [
            'clientId' => $this->clientId,
            'clientSecret' => $this->clientSecret,
            ]
        );

        $request = MessageFactoryDiscovery::find()->createRequest(
            'POST',
            'https://api.patreon.com/oauth2/token',
            [
                'Content-Type'  => 'application/x-www-form-urlencoded',
                'Accept'        => 'application/json',
                'User-Agent'    => '1f991/patreon-php',
                'form_params'   => $parameters,
            ]
        );

        $response = $this->httpClient->sendRequest($request);

        return json_decode((string) $response->getBody(), true);
    }
}
