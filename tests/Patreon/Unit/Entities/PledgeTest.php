<?php declare(strict_types=1);

namespace Squid\Patreon\Tests\Unit\Entities;

use Squid\Patreon\Entities\Pledge;
use Squid\Patreon\Entities\Reward;

class PledgeTest extends TestCase
{
    public function testCampaignEntityHasAllRequiredProperties()
    {
        $schema = [
            'amount_cents',
            'created_at',
            'declined_since',
            'has_shipping_address',
            'is_paused',
            'patron_pays_fees',
            'pledge_cap_cents',
            'total_historical_amount_cents',
            // Relations
            'address',
            'card',
            'creator',
            'patron',
            'reward',
        ];

        $this->assertTrue($this->validateEntitySchema(new Pledge, $schema));
    }

    public function testIsActiveReturnsExpectedResults(): void
    {
        $active = new Pledge;
        $active->is_paused = false;
        $active->declined_since = null;

        $declined = new Pledge;
        $declined->is_paused = false;
        $declined->declined_since = '2018-02-21T16:33:33.974297+00:00';

        $paused = new Pledge;
        $paused->is_paused = true;
        $paused->declined_since = null;

        $this->assertTrue($active->isActive());
        $this->assertFalse($declined->isActive());
        $this->assertFalse($paused->isActive());
    }

    public function testIsPaymentDeclinedReturnsExpectedResults(): void
    {
        $active = new Pledge;
        $active->declined_since = null;

        $declined = new Pledge;
        $declined->declined_since = '2018-02-21T16:33:33.974297+00:00';

        $this->assertTrue($declined->isPaymentDeclined());
        $this->assertFalse($active->isPaymentDeclined());
    }

    public function testHasMadeAPaymentReturnsExpectedResults(): void
    {
        $hasPaid = new Pledge;
        $hasPaid->total_historical_amount_cents = 100;

        $noPayment = new Pledge;
        $noPayment->total_historical_amount_cents = null;

        $this->assertTrue($hasPaid->hasMadeAPayment());
        $this->assertFalse($noPayment->hasMadeAPayment());
    }

    public function testHasRewardReturnsExpectedResults(): void
    {
        $hasReward = new Pledge;
        $hasReward->reward = new Reward;

        $noReward = new Pledge;

        $this->assertTrue($hasReward->hasReward());
        $this->assertFalse($noReward->hasReward());
    }

    public function testGetTotalSpentReturnsCurrencyFormattedAmount(): void
    {
        $pledge = new Pledge;
        $pledge->total_historical_amount_cents = 1649;

        $nothingSpent = new Pledge;

        $this->assertEquals('16.49', $pledge->getTotalSpent());
        $this->assertEquals('0.00', $nothingSpent->getTotalSpent());
    }
}
