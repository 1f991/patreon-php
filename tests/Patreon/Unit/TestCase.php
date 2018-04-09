<?php declare(strict_types=1);

namespace Squid\Patreon\Tests\Unit;

use Http\Mock\Client as MockHttpClient;
use PHPUnit\Framework\TestCase as PHPUnit_TestCase;
use Squid\Patreon\Api\Client;
use WoohooLabs\Yang\JsonApi\Schema\Document;
use WoohooLabs\Yang\JsonApi\Response\JsonApiResponse;

class TestCase extends PHPUnit_TestCase
{
    /**
     * Returns the contents of a fixture as a string.
     *
     * @param string $path Path to the fixture
     *
     * @return string
     */
    public function fixture(string $path): string
    {
        return file_get_contents(__DIR__ . '/../fixtures/' . $path);
    }

    /**
     * Returns a mock Client for a JSON Api Resource of type.
     *
     * @param string $type Type of resource
     *
     * @return Squid\Patreon\Api\Client
     */
    protected function createClientMockForResource(string $type): Client
    {
        $document = $this->createMock(JsonApiResponse::class);
        $document->expects($this->once())
            ->method('document')
            ->willReturn(Document::createFromArray(['data' => ['type' => $type]]));

        $client = $this->createMock(Client::class);
        $client->expects($this->once())
            ->method('get')
            ->willReturn($document);

        return $client;
    }
}
