<?php

declare(strict_types=1);

namespace Squid\Patreon\Tests\Unit\Entities;

use Squid\Patreon\Entities\Address;

class AddressTest extends TestCase
{
    public function testGoalEntityHasAllRequiredProperties()
    {
        $schema = [
            'addressee',
            'city',
            'country',
            'line_1',
            'line_2',
            'postal_code',
            'state',
        ];

        $this->assertTrue($this->validateEntitySchema(new Address(), $schema));
    }
}
