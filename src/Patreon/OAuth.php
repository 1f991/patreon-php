<?php

declare(strict_types=1);

namespace Squid\Patreon;

use Http\Client\HttpClient;
use Http\Discovery\HttpClientDiscovery;
use Http\Discovery\MessageFactoryDiscovery;
use Psr\Http\Message\RequestInterface;
use Squid\Patreon\Exceptions\OAuthReturnedError;
use Squid\Patreon\Exceptions\OAuthScopesAreInvalid;

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
        'my-campaign',
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
            'client_id'     => $this->clientId,
            'redirect_uri'  => $this->redirectUri,
            'scope'         => implode(' ', $scope),
            ]
        );

        return 'https://www.patreon.com/oauth2/authorize?'.$query;
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
            'code'         => $code,
            'grant_type'   => 'authorization_code',
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
            'grant_type'    => 'refresh_token',
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
            'client_id'     => $this->clientId,
            'client_secret' => $this->clientSecret,
            ]
        );

        $response = $this->httpClient->sendRequest(
            $this->createTokenRequest($parameters)
        );

        $result = json_decode((string) $response->getBody());

        if ($result->error ?? false) {
            throw OAuthReturnedError::error($result->error);
        }

        return [
            'access_token'  => $result->access_token,
            'refresh_token' => $result->refresh_token,
            'expires_in'    => $result->expires_in,
            'scope'         => $result->scope,
            'token_type'    => $result->token_type,
        ];
    }

    /**
     * Creates a Token Request.
     *
     * @param array $parameters Token request parameters.
     *
     * @return \Psr\Http\Message\RequestInterface
     */
    protected function createTokenRequest($parameters): RequestInterface
    {
        return MessageFactoryDiscovery::find()->createRequest(
            'POST',
            'https://api.patreon.com/oauth2/token',
            [
                'Content-Type'  => 'application/x-www-form-urlencoded',
                'Accept'        => 'application/json',
                'User-Agent'    => '1f991/patreon-php',
            ],
            http_build_query($parameters)
        );
    }
}
