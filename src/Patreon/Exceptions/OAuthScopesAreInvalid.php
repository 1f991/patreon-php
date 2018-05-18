<?php

declare(strict_types=1);

namespace Squid\Patreon\Exceptions;

use LogicException;

class OAuthScopesAreInvalid extends LogicException
{
    /**
     * Provides an error for when OAuth scopes are invalid.
     *
     * @param array $invalid List of invalid scopes provided.
     * @param array $valid   List of valid scopes that can be provided.
     *
     * @return \Squid\Patreon\Exceptions\OAuthScopesAreInvalid
     */
    public static function scopes(array $invalid, array $valid): self
    {
        $message = 'Provided scopes are invalid: '.implode(', ', $invalid).'.';
        $message .= ' Valid scopes are: '.implode(', ', $valid);

        return new static($message);
    }
}
