<?php declare(strict_types=1);

namespace Squid\Patreon\Exceptions;

use LogicException;

class ResourceRequiresAuthentication extends LogicException
{
    /**
     * Provides an error for when a Resource must be accessed via an authenticated
     * endpoint.
     *
     * @param string $resource Name of the Resource class.
     * @param string $method   Name of the method.
     *
     * @return \Squid\Patreon\Exceptions\ResourceRequiresAuthentication
     */
    public static function forMethod(string $resource, string $method): self
    {
        $message = "{$method} on {$resource} is only accessible when authenticated.";

        return new static($message);
    }
}
