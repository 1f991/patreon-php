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
            'discord_role_ids',
            'edited_at',
            'image_url',
            'patron_count',
            'post_count',
            'published',
            'published_at',
            'remaining',
            'requires_shipping',
            'title',
            'unpublished_at',
            'url',
            'user_limit',
        ];

        $this->assertTrue($this->validateEntitySchema(new Reward, $schema));
    }

    public function testIsAvailableToChooseReturnsExpectedResults(): void
    {
        $limitReached = new Reward;
        $limitReached->id = '1';
        $limitReached->user_limit = 10;
        $limitReached->remaining = 0;

        $noLimit = new Reward;
        $noLimit->id = '1';
        $noLimit->user_limit = null;
        $noLimit->remaining = null;

        $systemReward = new Reward;
        $systemReward->id = '0';

        $limitNotReached = new Reward;
        $limitNotReached->id = '1';
        $limitNotReached->user_limit = 10;
        $limitNotReached->remaining = 5;

        $this->assertFalse($limitReached->isAvailableToChoose());
        $this->assertTrue($noLimit->isAvailableToChoose());
        $this->assertFalse($systemReward->isAvailableToChoose());
        $this->assertTrue($limitNotReached->isAvailableToChoose());
    }

    public function testHasRemainingLimitReturnsExpectedResults(): void
    {
        $limitReached = new Reward;
        $limitReached->id = '1';
        $limitReached->user_limit = 10;
        $limitReached->remaining = 0;

        $noLimit = new Reward;
        $noLimit->id = '1';
        $noLimit->user_limit = null;
        $noLimit->remaining = null;

        $limitNotReached = new Reward;
        $limitNotReached->id = '1';
        $limitNotReached->user_limit = 10;
        $limitNotReached->remaining = 5;

        $this->assertFalse($limitReached->isAvailableToChoose());
        $this->assertTrue($noLimit->isAvailableToChoose());
        $this->assertTrue($limitNotReached->isAvailableToChoose());
    }

    public function testIsSystemRewardReturnsExpectedResults(): void
    {
        $systemReward = new Reward;
        $systemReward->id = '0';

        $campaignReward = new Reward;
        $campaignReward->id = '100';

        $this->assertTrue($systemReward->isSystemReward());
        $this->assertFalse($campaignReward->isSystemReward());
    }
}
