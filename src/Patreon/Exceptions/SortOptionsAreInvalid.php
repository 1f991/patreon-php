<?php

declare(strict_types=1);

namespace Squid\Patreon\Exceptions;

use LogicException;

class SortOptionsAreInvalid extends LogicException
{
    /**
     * Provides an error for when sort options are invalid.
     *
     * @param array $invalid List of invalid options provided.
     * @param array $valid   List of valid options that can be provided.
     *
     * @return \Squid\Patreon\Exceptions\SortOptionsAreInvalid
     */
    public static function options(array $invalid, array $valid): self
    {
        $message = 'Provided options are invalid: '.implode(', ', $invalid).'.';
        $message .= ' Valid options are: '.implode(', ', $valid);

        return new static($message);
    }
}
