<?php

declare(strict_types=1);

namespace Squid\Patreon\Exceptions;

use RuntimeException;

class SignatureVerificationFailed extends RuntimeException
{
    /**
     * Provides an error for when the signature provided does not verify.
     *
     * @param string $actual Signature given for the payload.
     *
     * @return \Squid\Patreon\Exceptions\SignatureVerificationFailed
     */
    public static function withSignature(string $actual): self
    {
        $message = "Received {$actual} as signature but it did not verify.";

        return new static($message);
    }
}
