<?php declare(strict_types=1);

namespace Squid\Patreon\Api;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\RequestInterface;
use Http\Client\HttpClient;
use Http\Discovery\HttpClientDiscovery;
use Http\Discovery\MessageFactoryDiscovery;
use WoohooLabs\Yang\JsonApi\Client\JsonApiClient;

class Client
{
    /**
    * Constant representing the Patreon API endpoint.
    *
    * @var string
    */
    const API_ENDPOINT = 'https://api.patreon.com/';

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
    public function __construct(?HttpClient $http = null)
    {
        $this->jsonApi = new JsonApiClient(
            $http ?: HttpClientDiscovery::find()
        );
    }

    /**
     * Sets the access token.
     *
     * @param string $accessToken Access Token
     *
     * @return void
     */
    public function setAccessToken(string $accessToken): void
    {
        $this->accessToken = $accessToken;
    }

    /**
     * Executes a GET request to the Patreon API.
     *
     * @param string $path API request path
     *
     * @return \Psr\Http\Message\ResponseInterface
     */
    public function get(string $path): ResponseInterface
    {
        return $this->jsonApi->sendRequest(
            $this->makeRequest('GET', $path)
        );
    }

    /**
     * Makes a Request.
     *
     * @param string $method API request method
     * @param string $path   API request path
     *
     * @return \Psr\Http\Message\RequestInterface
     */
    protected function makeRequest(string $method, string $path): RequestInterface
    {
        $messageFactory = MessageFactoryDiscovery::find();

        return $messageFactory->createRequest(
            $method,
            self::API_ENDPOINT . $path,
            [
                'Authorization' => 'Bearer ' . $this->accessToken,
                'Accept'        => 'application/json',
                'User-Agent'    => '1f991/patreon-php'
            ]
        );
    }
}
