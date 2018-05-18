<?php

declare(strict_types=1);

namespace Squid\Patreon\Exceptions;

use RuntimeException;

class PatreonReturnedError extends RuntimeException
{
    /**
     * Provides an error for when Patreon API responds with an error.
     *
     * @param string $title Title of the error
     * @param string $body  Body of the error
     *
     * @return \Squid\Patreon\Exceptions\PatreonReturnedError
     */
    public static function error(string $title, string $body): self
    {
        return new static("{$title} {$body}");
    }
}
