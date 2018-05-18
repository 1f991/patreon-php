<?php

declare(strict_types=1);

namespace Squid\Patreon\Tests\Unit\Resources;

use Squid\Patreon\Api\Client;
use Squid\Patreon\Entities\User;
use Squid\Patreon\Exceptions\ResourceRequiresAuthentication;
use Squid\Patreon\Resources\CurrentUser;
use Squid\Patreon\Tests\Unit\TestCase;

class CurrentUserTest extends TestCase
{
    public function testMeReturnsHydratedUserEntity(): void
    {
        $client = $this->createClientMockForResource('user');

        $user = (new CurrentUser($client))->get();

        $this->assertInstanceOf(User::class, $user);
    }

    public function testMeThrowsExceptionWithoutAuthentication(): void
    {
        $client = $this->createMock(Client::class);

        $this->expectException(ResourceRequiresAuthentication::class);

        (new CurrentUser($client, false))->get();
    }
}
