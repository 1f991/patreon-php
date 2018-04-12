<?php declare(strict_types=1);

namespace Squid\Patreon\Resources;

use Squid\Patreon\Entities\Entity;
use Squid\Patreon\Exceptions\SignatureVerificationFailed;
use WoohooLabs\Yang\JsonApi\Schema\Document;

class Webhook extends Resource
{
    /**
     * Accept an incoming Webhook, verify the signature and return an entity.
     *
     * @param string $body      Request body (JSON)
     * @param string $secret    Secret used to generate the signature
     * @param string $signature Signature of the request
     *
     * @return Squid\Patreon\Entities\Entity
     */
    public function accept(
        string $body,
        string $secret,
        string $signature
    ): Entity {
        $this->verifySignature($body, $secret, $signature);

        return $this->hydrateDocument(
            Document::createFromArray(json_decode($body, true))
        )->first();
    }

    /**
     * Verify a webhook signature.
     *
     * @param string $body      Request body (JSON)
     * @param string $secret    Secret used to generate the signature
     * @param string $signature Signature of the request
     *
     * @throws Exception
     *
     * @return bool
     */
    public function verifySignature(
        string $body,
        string $secret,
        string $signature
    ): bool {
        $expected = hash_hmac('md5', $body, $secret);

        if ($expected === $signature) {
            return true;
        }

        throw SignatureVerificationFailed::withSignature($expected, $signature);
    }
}
