<?php

declare(strict_types=1);

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
            // Relations
            'campaign',
        ];

        $this->assertTrue($this->validateEntitySchema(new Reward(), $schema));
    }

    public function testIsAvailableToChooseReturnsExpectedResults(): void
    {
        $limitReached = new Reward();
        $limitReached->id = '1';
        $limitReached->user_limit = 10;
        $limitReached->remaining = 0;

        $noLimit = new Reward();
        $noLimit->id = '1';
        $noLimit->user_limit = null;
        $noLimit->remaining = null;

        $systemReward = new Reward();
        $systemReward->id = '0';

        $limitNotReached = new Reward();
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
        $limitReached = new Reward();
        $limitReached->id = '1';
        $limitReached->user_limit = 10;
        $limitReached->remaining = 0;

        $noLimit = new Reward();
        $noLimit->id = '1';
        $noLimit->user_limit = null;
        $noLimit->remaining = null;

        $limitNotReached = new Reward();
        $limitNotReached->id = '1';
        $limitNotReached->user_limit = 10;
        $limitNotReached->remaining = 5;

        $this->assertFalse($limitReached->isAvailableToChoose());
        $this->assertTrue($noLimit->isAvailableToChoose());
        $this->assertTrue($limitNotReached->isAvailableToChoose());
    }

    public function testIsSystemRewardReturnsExpectedResults(): void
    {
        $systemReward = new Reward();
        $systemReward->id = '0';

        $campaignReward = new Reward();
        $campaignReward->id = '100';

        $this->assertTrue($systemReward->isSystemReward());
        $this->assertFalse($campaignReward->isSystemReward());
    }

    public function testGetPledgeUrlReturnsCorrectValue(): void
    {
        $reward = new Reward();
        $reward->url = '/bePatron?c=1&rid=1';

        $this->assertEquals(
            'https://patreon.com/bePatron?c=1&rid=1',
            $reward->getPledgeUrl()
        );
    }

    public function testSystemRewardIsNotAttached(): void
    {
        $systemReward = new Reward();
        $realReward = new Reward();
        $realReward->id = 1;

        $this->assertFalse($systemReward->shouldAttach());
        $this->assertTrue($realReward->shouldAttach());
    }

    public function testGetPrice(): void
    {
        $reward = new Reward();
        $reward->amount_cents = 1298;

        $this->assertEquals('12.98', $reward->getPrice());
    }
}
