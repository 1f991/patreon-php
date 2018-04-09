<?php declare(strict_types=1);

namespace Squid\Patreon\Tests\Unit\Resources;

use Http\Mock\Client as MockHttpClient;
use Psr\Http\Message\ResponseInterface;
use Squid\Patreon\Api\Client;
use Squid\Patreon\Entities\Entity;
use Squid\Patreon\Resources\CurrentUser;
use Squid\Patreon\Tests\Unit\TestCase;
use WoohooLabs\Yang\JsonApi\Schema\Document;
use WoohooLabs\Yang\JsonApi\Response\JsonApiResponse;

class CurrentUserTest extends TestCase
{
    public function testMeReturnsHydratedUserEntity(): void
    {
        $document = $this->createMock(JsonApiResponse::class);
        $document->expects($this->once())
            ->method('document')
            ->willReturn(
                Document::createFromArray(
                    json_decode($this->fixture('1/current_user.json'), true)
                )
            );

        $client = $this->createMock(Client::class);
        $client->expects($this->once())
            ->method('get')
            ->willReturn($document);

        $user = (new CurrentUser($client))->get();

        $this->assertInstanceOf(Entity::class, $user);
    }
}
