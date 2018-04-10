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
        ];

        $this->assertTrue($this->validateEntitySchema(new Pledge, $schema));
    }
}
