<?php declare(strict_types=1);

namespace Squid\Patreon\Tests\Unit\Entities;

use Squid\Patreon\Entities\Campaign;
use Squid\Patreon\Entities\Reward;
use Tightenco\Collect\Support\Collection;

class CampaignTest extends TestCase
{
    public function testCampaignEntityHasAllRequiredProperties()
    {
        $schema = [
            'created_at',
            'creation_count',
            'creation_name',
            'discord_server_id',
            'display_patron_goals',
            'earnings_visibility',
            'image_small_url',
            'image_url',
            'is_charge_upfront',
            'is_charged_immediately',
            'is_first_month_upfront',
            'is_monthly',
            'is_nsfw',
            'is_plural',
            'main_video_embed',
            'main_video_url',
            'one_liner',
            'outstanding_payment_amount_cents',
            'patron_count',
            'pay_per_name',
            'pledge_sum',
            'pledge_url',
            'published_at',
            'summary',
            'thanks_embed',
            'thanks_msg',
            'thanks_video_url',
            // Relations
            'creator',
            'goals',
            'pledges',
            'rewards',
        ];

        $this->assertTrue($this->validateEntitySchema(new Campaign, $schema));
    }

    public function testGetPledgeUrlReturnsCorrectValue(): void
    {
        $campaign = new Campaign;
        $campaign->pledge_url = '/bePatron?c=1';

        $this->assertEquals(
            'https://patreon.com/bePatron?c=1',
            $campaign->getPledgeUrl()
        );
    }

    public function testGetAvailableRewardsProvidesRewards(): void
    {
        $availableReward = new Reward;
        $availableReward->id = 1;
        $unavailableReward = new Reward;
        $unavailableReward->remaining = 0;

        $campaign = new Campaign;
        $campaign->rewards = new Collection(
            [
            $availableReward,
            $unavailableReward,
            $availableReward,
            ]
        );

        $this->assertCount(2, $campaign->getAvailableRewards());
    }
}
