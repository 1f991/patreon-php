<?php declare(strict_types=1);

namespace Squid\Patreon\Exceptions;

use RuntimeException;

class OAuthReturnedError extends RuntimeException
{
    /**
     * Provides an error for when Patreon OAuth responds with an error.
     *
     * @param string $error  Body of the error
     *
     * @return \Squid\Patreon\Exceptions\OAuthReturnedError
     */
    public static function error(string $error): self
    {
        return new static($error);
    }
}
