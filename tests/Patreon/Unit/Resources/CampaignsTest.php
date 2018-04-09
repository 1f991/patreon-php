<?php declare(strict_types=1);

namespace Squid\Patreon\Tests\Unit\Resources;

use Squid\Patreon\Entities\Campaign;
use Squid\Patreon\Resources\Campaigns;
use Squid\Patreon\Tests\Unit\TestCase;

class CampaignsTest extends TestCase
{
    public function testGetMyCampaignReturnsHydratedCampaignEntity(): void
    {
        $client = $this->createClientMockForResource('campaign');

        $campaign = (new Campaigns($client))->getMyCampaign();

        $this->assertInstanceOf(Campaign::class, $campaign);
    }
}
