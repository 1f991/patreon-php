<?php declare(strict_types=1);

namespace Squid\Patreon\Tests\Unit\Resources;

use Exception;
use Squid\Patreon\Api\Client;
use Squid\Patreon\Entities\User;
use Squid\Patreon\Exceptions\UnauthorizedException;
use Squid\Patreon\Resources\Resource;
use Squid\Patreon\Tests\Unit\TestCase;
use WoohooLabs\Yang\JsonApi\Schema\Document;
use WoohooLabs\Yang\JsonApi\Response\JsonApiResponse;

class ResourceTest extends TestCase
{
    public function testResourceThrowsExceptionWhenApiReturnsError(): void
    {
        $document = Document::createFromArray(
            [
            'errors' => [
                ['code_name' => '', 'detail' => 'example'],
            ],
            ]
        );

        $response = $this->createMock(JsonApiResponse::class);
        $response->method('document')->willReturn($document);

        $client = $this->createMock(Client::class);

        $this->expectException(Exception::class);

        $user = (new ResourceExample($client))->get($response);
    }
}

class ResourceExample extends Resource
{
    public function get(JsonApiResponse $response): void
    {
        $this->hydrateDocument($response->document());
    }
}
