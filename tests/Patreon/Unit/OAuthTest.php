<?php declare(strict_types=1);

namespace Squid\Patreon\Tests\Unit;

use Http\Mock\Client as MockHttpClient;
use Psr\Http\Message\ResponseInterface;
use Squid\Patreon\Exceptions\OAuthScopesAreInvalid;
use Squid\Patreon\OAuth;
use Squid\Patreon\Tests\Unit\TestCase;

class OAuthTest extends TestCase
{
    public function testGetAuthorizationUrlReturnsExpectedUrl(): void
    {
        $http = new MockHttpClient;

        $oauth = new OAuth('id', 'secret', 'redirect', $http);

        $expectedUrl = 'https://www.patreon.com/oauth2/authorize?response_type=code';
        $expectedUrl .= '&client_id=id&redirect_uri=redirect';
        $expectedUrl .= '&scope=users+pledges-to-me+my-campaign';

        $this->assertEquals($expectedUrl, $oauth->getAuthorizationUrl());
    }

    public function testGetAuthorizationUrlThrowsExceptionForInvalidScopes(): void
    {
        $http = new MockHttpClient;

        $oauth = new OAuth('id', 'secret', 'uri', $http);

        $this->expectException(OAuthScopesAreInvalid::class);

        $oauth->getAuthorizationUrl(['invalid-scope']);
    }

    public function testGetAccessTokenMakesApiRequest(): void
    {
        $http = new MockHttpClient;

        $response = $this->createMock(ResponseInterface::class);
        $response->expects($this->once())
            ->method('getBody')
            ->willReturn('{"access_token": "123", "refresh_token": "456"}');

        $http->addResponse($response);

        $oauth = new OAuth('id', 'secret', 'redirect', $http);

        $tokens = $oauth->getAccessToken('code');

        $this->assertEquals('123', $tokens['access_token']);
        $this->assertEquals('456', $tokens['refresh_token']);
    }

    public function testGetNewTokenMakesApiRequest(): void
    {
        $http = new MockHttpClient;

        $response = $this->createMock(ResponseInterface::class);
        $response->expects($this->once())
            ->method('getBody')
            ->willReturn('{"access_token": "123", "refresh_token": "456"}');

        $http->addResponse($response);

        $oauth = new OAuth('id', 'secret', 'redirect', $http);

        $tokens = $oauth->getNewToken('access_token');

        $this->assertEquals('123', $tokens['access_token']);
        $this->assertEquals('456', $tokens['refresh_token']);
    }
}
