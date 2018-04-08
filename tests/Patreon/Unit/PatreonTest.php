<?php declare(strict_types=1);

namespace Squid\Patreon\Tests\Unit\Api;

use Squid\Patreon\Api\Client;
use Squid\Patreon\Patreon;
use Squid\Patreon\Tests\Unit\TestCase;

class PatreonTest extends TestCase
{
    public function testAccessTokenIsSet()
    {
        $client = $this->createMock(Client::class);
        $client->expects($this->once())
            ->method('setAccessToken')
            ->with('access-token');

        new Patreon('access-token', $client);
    }
}
