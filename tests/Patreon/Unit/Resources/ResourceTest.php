<?php declare(strict_types=1);

namespace Squid\Patreon\Tests\Unit\Resources;

use Squid\Patreon\Api\Client;
use Squid\Patreon\Entities\Entity;
use Squid\Patreon\Entities\User;
use Squid\Patreon\Exceptions\PatreonReturnedError;
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

        $this->expectException(PatreonReturnedError::class);

        (new ExampleResource($client))->get($response);
    }

    public function testBuildUrlReturnsCorrectUrl(): void
    {
        $client = $this->createMock(Client::class);
        $resource = (new ExampleResource($client));

        $this->assertEquals(
            'example?fields%5Bentity%5D=a%2Cb%2Cc%2Cd%2Cid',
            $resource->url()
        );

        $this->assertEquals(
            'example?x=5&fields%5Bentity%5D=a%2Cb%2Cc%2Cd%2Cid',
            $resource->url(['x' => 5])
        );
    }
}

class ExampleResource extends Resource
{
    public function get(JsonApiResponse $response): void
    {
        $this->hydrateDocument($response->document());
    }

    public function url($parameters = []): string
    {
        return $this->buildUrl('example', ExampleEntity::class, $parameters);
    }
}

class ExampleEntity extends Entity
{
    protected $type = 'entity';
    public $a;
    public $b;
    public $c;
    public $d;
}
