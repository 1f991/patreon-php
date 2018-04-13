<?php declare(strict_types=1);

namespace Squid\Patreon\Tests\Unit\Entities;

use Squid\Patreon\Entities\Card;

class CardTest extends TestCase
{
    public function testGoalEntityHasAllRequiredProperties()
    {
        $schema = [
            'card_type',
            'created_at',
            'expiration_date',
            'has_a_failed_payment',
            'is_verified',
            'number',
            'payment_token',
            'payment_token_id',
        ];

        $this->assertTrue($this->validateEntitySchema(new Card, $schema));
    }
}
