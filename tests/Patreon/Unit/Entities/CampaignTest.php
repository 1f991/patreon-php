<?php declare(strict_types=1);

namespace Squid\Patreon\Tests\Unit\Entities;

use Squid\Patreon\Entities\Campaign;

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
        ];

        $this->assertTrue($this->validateEntitySchema(new Campaign, $schema));
    }
}
