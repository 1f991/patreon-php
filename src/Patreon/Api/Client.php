<?php declare(strict_types=1);

namespace Squid\Patreon\Api;

use GuzzleHttp\Psr7\Request;
use GuzzleHttp\Psr7\Response;
use Http\Client\HttpClient;
use Http\Discovery\HttpClientDiscovery;
use Http\Discovery\MessageFactoryDiscovery;

class Client
{
    /**
    * Constant representing the Patreon API endpoint.
    *
    * @var string
    */
    const API_ENDPOINT = 'https://api.patreon.com/oauth2/api/';

    /**
     * Access token to be included in the requests to the Patreon API.
     *
     * @var string
     */
    protected $accessToken;

    /**
     * Constructs a new Patreon API Client.
     *
     * @param HttpClient $http Http Client
     *
     * @return void
     */
    public function __construct(HttpClient $http = null)
    {
        $this->http = $http ?: HttpClientDiscovery::find();
    }

    /**
     * Sets the access token.
     *
     * @param string $accessToken Access Token
     *
     * @return void
     */
    public function setAccessToken($accessToken): void
    {
        $this->accessToken = $accessToken;
    }

    /**
     * Executes a GET request to the Patreon API.
     *
     * @param string $path API request path
     *
     * @return \GuzzleHttp\Psr7\Response
     */
    public function get($path): Response
    {
        return $this->http->sendRequest(
            $this->makeRequest('GET', $path)
        );
    }

    /**
     * Makes a Request.
     *
     * @param string $method API request method
     * @param string $path   API request path
     *
     * @return \GuzzleHttp\Psr7\Request
     */
    protected function makeRequest($method, $path): Request
    {
        $messageFactory = MessageFactoryDiscovery::find();

        return $messageFactory->createRequest(
            $method,
            self::API_ENDPOINT . $path,
            [
                'Authorization' => 'Bearer ' . $this->accessToken,
                'Accept'        => 'application/json'
            ]
        );
    }
}
