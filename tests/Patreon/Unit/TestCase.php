<?php

declare(strict_types=1);

namespace Squid\Patreon\Tests\Unit;

use PHPUnit\Framework\TestCase as PHPUnit_TestCase;
use Squid\Patreon\Api\Client;
use WoohooLabs\Yang\JsonApi\Response\JsonApiResponse;
use WoohooLabs\Yang\JsonApi\Schema\Document;

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
        return file_get_contents(__DIR__.'/../fixtures/'.$path);
    }

    /**
     * Returns a mock Client for a JSON Api Resource of type.
     *
     * @param string $type Type of resource
     *
     * @return Squid\Patreon\Api\Client
     */
    protected function createClientMockForResource(
        string $type,
        int $amount = 1
    ): Client {
        $document = $this->createJsonApiMockWithDocumentForResource($type, $amount);

        $client = $this->createMock(Client::class);
        $client->expects($this->once())
            ->method('get')
            ->willReturn($document);

        return $client;
    }

    /**
     * Returns a JSON Api Response Mock that has a Document containing
     * x resources of type.
     *
     * @param string $type   Resource type
     * @param int    $amount Amount of resources to include in the document
     *
     * @return WoohooLabs\Yang\JsonApi\Schema\Document
     */
    protected function createJsonApiMockWithDocumentForResource(
        string $type,
        int $amount = 1
    ): JsonApiResponse {
        $data = [];

        foreach (range(1, $amount) as $i) {
            $data[] = ['type' => $type, 'id' => (string) $i];
        }

        $document = $this->createMock(JsonApiResponse::class);
        $document->method('document')
            ->willReturn(Document::createFromArray(['data' => $data]));

        return $document;
    }
}
