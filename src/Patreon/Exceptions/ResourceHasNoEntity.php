<?php

declare(strict_types=1);

namespace Squid\Patreon\Exceptions;

use LogicException;

class ResourceHasNoEntity extends LogicException
{
    /**
     * Provides an error for when a Resource does not have an associated Entity.
     *
     * @param string $name Name of the Resource.
     *
     * @return \Squid\Patreon\Exceptions\ResourceHasNoEntity
     */
    public static function forResource(string $name): self
    {
        $message = "No entity is configured for {$name} resources.";

        return new static($message);
    }
}
