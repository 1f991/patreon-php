<?php declare(strict_types=1);

namespace Squid\Patreon\Tests\Unit\Entities;

use Squid\Patreon\Entities\Reward;

class RewardTest extends TestCase
{
    public function testRewardEntityHasAllRequiredProperties()
    {
        $schema = [
            'amount',
            'amount_cents',
            'created_at',
            'description',
            'remaining',
            'requires_shipping',
            'url',
            'user_limit',
        ];

        $this->assertTrue($this->validateEntitySchema(new Reward, $schema));
    }
}
