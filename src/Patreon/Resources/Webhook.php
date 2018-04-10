<?php declare(strict_types=1);

namespace Squid\Patreon\Resources;

use Exception;
use Squid\Patreon\Entities\Entity;
use WoohooLabs\Yang\JsonApi\Schema\Document;

class Webhook extends Resource
{
    /**
     * Accept an incoming Webhook, validate the signature and return an entity.
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
        $this->validateSignature($body, $secret, $signature);

        return $this->hydrateDocument(
            Document::createFromArray(json_decode($body, true))
        )->first();
    }

    /**
     * Validate a webhook signature.
     *
     * @param string $body      Request body (JSON)
     * @param string $secret    Secret used to generate the signature
     * @param string $signature Signature of the request
     *
     * @throws Exception
     *
     * @return boolean
     */
    public function validateSignature(
        string $body,
        string $secret,
        string $signature
    ): bool {
        if (hash_hmac('md5', $body, $secret) === $signature) {
            return true;
        }

        throw new Exception('Signature validation failed.');
    }
}
