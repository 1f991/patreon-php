<?php declare(strict_types=1);

namespace Squid\Patreon\Tests\Unit\Resources;

use Squid\Patreon\Api\Client;
use Squid\Patreon\Entities\Pledge;
use Squid\Patreon\Exceptions\SignatureVerificationFailed;
use Squid\Patreon\Resources\Webhook;
use Squid\Patreon\Tests\Unit\TestCase;
use WoohooLabs\Yang\JsonApi\Schema\Document;

class WebhookTest extends TestCase
{
    public function testWebhookThrowsExceptionWhenSignatureVerificationFails(): void
    {
        $client = $this->createMock(Client::class);

        $this->expectException(SignatureVerificationFailed::class);

        (new Webhook($client))->verifySignature(
            'Hello World!',
            'invalid-secret',
            'invalid-signature'
        );
    }

    public function testWebhookSignatureVerificationPassesWithValidSignature(): void
    {
        $client = $this->createMock(Client::class);

        $signature = (new Webhook($client))->verifySignature(
            'Hello World!',
            'secret-key',
            '7a42ad6672deb0bd44a439d2e786def0'
        );

        $this->assertTrue($signature);
    }

    public function testWebhookDocumentIsHydrated(): void
    {
        $client = $this->createMock(Client::class);

        $document = [
            'data' => [
                ['type' => 'pledge', 'id' => '1'],
            ]
        ];

        $pledge = (new Webhook($client))->accept(
            json_encode($document),
            'secret-key',
            'e8385c1684ed540fcc8d2771dee616ee'
        );

        $this->assertInstanceOf(Pledge::class, $pledge);
    }
}
