<?php declare(strict_types=1);

namespace Squid\Patreon\Tests\Unit\Entities;

use Squid\Patreon\Entities\Pledge;

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

    public function testIsPledgeActiveReturnsExpectedResults(): void
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

        $this->assertTrue($active->isPledgeActive());
        $this->assertFalse($declined->isPledgeActive());
        $this->assertFalse($paused->isPledgeActive());
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
}
