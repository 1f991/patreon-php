<?php declare(strict_types=1);

namespace Squid\Patreon\Exceptions;

use LogicException;

class SortOptionsAreInvalid extends LogicException
{
    /**
     * Provides an erorr for when a sort option is invalid.
     *
     * @return \Squid\Patreon\Exceptions\SortOptionsAreInvalid
     */
    public static function options(array $invalid, array $valid): self
    {
        $message = "Some provided options are invalid: " . implode(', ', $invalid) . ".";
        $message .= " Valid options are: " . implode(', ', $valid);

        return new static($message);
    }
}
