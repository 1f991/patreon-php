<?php declare(strict_types=1);

namespace Squid\Patreon\Exceptions;

use RuntimeException;

class SignatureVerificationFailed extends RuntimeException
{
    /**
     * Provides an error for when the signature provided does not verify.
     *
     * @param string $expected Signature expected for the payload.
     * @param string $actual   Signature given for the payload.
     *
     * @return \Squid\Patreon\Exceptions\PatreonReturnedError
     */
    public static function withSignature(string $expected, string $actual): self
    {
        $message = "Expected {$expected} but received {$actual}.";

        return new static($message);
    }
}
