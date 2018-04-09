<?php declare(strict_types=1);

namespace Squid\Patreon\Tests\Unit\Resources;

use Squid\Patreon\Entities\User;
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
}
