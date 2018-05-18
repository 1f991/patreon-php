<?php

declare(strict_types=1);

namespace Squid\Patreon\Tests\Unit\Api;

use Http\Mock\Client as MockHttpClient;
use Squid\Patreon\Api\Client;
use Squid\Patreon\Tests\Unit\TestCase;

class ClientTest extends TestCase
{
    public function testClientMakesRequestToAuthenticatedEndpoint()
    {
        $http = new MockHttpClient();

        $client = new Client($http);
        $client->get('example');

        $this->assertEquals(
            Client::API_ENDPOINT.'oauth2/api/example',
            (string) $http->getRequests()[0]->getUri()
        );
    }

    public function testClientMakesRequestToUnauthenticatedEndpoint()
    {
        $http = new MockHttpClient();

        $client = new Client($http);
        $client->get('example', false);

        $this->assertEquals(
            Client::API_ENDPOINT.'example',
            (string) $http->getRequests()[0]->getUri()
        );
    }

    public function testClientMakesRequestWithAccessToken()
    {
        $http = new MockHttpClient();

        $client = new Client($http);
        $client->setAccessToken('access-token');
        $client->get('example');

        $this->assertEquals(
            'Bearer access-token',
            $http->getRequests()[0]->getHeader('Authorization')[0]
        );
    }
}
