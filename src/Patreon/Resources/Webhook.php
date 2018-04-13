<?php declare(strict_types=1);

namespace Squid\Patreon\Resources;

use Squid\Patreon\Exceptions\SignatureVerificationFailed;
use Tightenco\Collect\Support\Collection;
use WoohooLabs\Yang\JsonApi\Schema\Document;

class Webhook extends Resource
{
    /**
     * Accept an incoming Webhook, verify the signature and return a Collection
     * of Entities.
     *
     * @param string $body      Request body (JSON)
     * @param string $secret    Secret used to generate the signature
     * @param string $signature Signature of the request
     *
     * @return \Tightenco\Collect\Support\Collection
     */
    public function accept(
        string $body,
        string $secret,
        string $signature
    ): Collection {
        $this->verifySignature($body, $secret, $signature);

        return $this->hydrateDocument(
            Document::createFromArray(json_decode($body, true))
        );
    }

    /**
     * Verify a webhook signature.
     *
     * @param string $body      Request body (JSON)
     * @param string $secret    Secret used to generate the signature
     * @param string $signature Signature of the request
     *
     * @throws \Squid\Patreon\Exceptions\SignatureVerificationFailed
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
