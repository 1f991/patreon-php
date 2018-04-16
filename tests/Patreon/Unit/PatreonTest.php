<?php declare(strict_types=1);

namespace Squid\Patreon\Tests\Unit;

use Squid\Patreon\Api\Client;
use Squid\Patreon\Patreon;
use Squid\Patreon\Resources\Campaigns;
use Squid\Patreon\Resources\CurrentUser;
use Squid\Patreon\Resources\Pledges;
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

    public function testClientReturnsResources(): void
    {
        $client = $this->createMock(Client::class);
        $patreon = new Patreon('access-token', $client);

        $this->assertInstanceOf(CurrentUser::class, $patreon->me());
        $this->assertInstanceOf(Campaigns::class, $patreon->campaigns());
        $this->assertInstanceOf(Pledges::class, $patreon->pledges());
    }

    public function testResourceIsCreatedWithoutAuthentication(): void
    {
        $patreon = new Patreon('access-token', $this->createMock(Client::class));
        $patreon->useUnauthenticatedResources();

        $this->assertFalse($patreon->campaigns()->isAuthenticated());
    }
}
