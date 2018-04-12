<?php declare(strict_types=1);

namespace Squid\Patreon\Tests\Unit\Resources;

use Squid\Patreon\Api\Client;
use Squid\Patreon\Entities\Campaign;
use Squid\Patreon\Exceptions\ResourceRequiresAuthentication;
use Squid\Patreon\Resources\Campaigns;
use Squid\Patreon\Tests\Unit\TestCase;
use WoohooLabs\Yang\JsonApi\Schema\Document;

class CampaignsTest extends TestCase
{
    public function testGetMyCampaignReturnsHydratedCampaignEntity(): void
    {
        $client = $this->createClientMockForResource('campaign');

        $campaign = (new Campaigns($client))->getMyCampaign();

        $this->assertInstanceOf(Campaign::class, $campaign);
    }

    public function testGetMyCampaignWithPledgesAttachesPledgesToEntity(): void
    {
        $campaign = $this->createJsonApiMockWithDocumentForResource('campaign');
        $pledges = $this->createJsonApiMockWithDocumentForResource('pledge', 10);

        $client = $this->createMock(Client::class);
        $client->method('get')
            ->will($this->onConsecutiveCalls($campaign, $pledges));

        $campaign = (new Campaigns($client))->getMyCampaignWithPledges();

        $this->assertCount(10, $campaign->pledges);
    }

    public function testGetCampaignReturnsHydratedCampaignEntity(): void
    {
        $client = $this->createClientMockForResource('campaign');

        $campaign = (new Campaigns($client))->getCampaign(1);

        $this->assertInstanceOf(Campaign::class, $campaign);
    }

    public function testGetCampaignWithPledgesAttachesPledgesToEntity(): void
    {
        $campaign = $this->createJsonApiMockWithDocumentForResource('campaign');
        $pledges = $this->createJsonApiMockWithDocumentForResource('pledge', 10);

        $client = $this->createMock(Client::class);
        $client->method('get')
            ->will($this->onConsecutiveCalls($campaign, $pledges));

        $campaign = (new Campaigns($client))->getCampaignWithPledges(1);

        $this->assertCount(10, $campaign->pledges);
    }

    public function testMyCampaignThrowsExceptionWithoutAuthentication(): void
    {
        $client = $this->createMock(Client::class);

        $this->expectException(ResourceRequiresAuthentication::class);

        (new Campaigns($client, false))->getMyCampaign();
    }

    public function testMyCampaignWithPledgesThrowsExceptionNotAuthenticated(): void
    {
        $client = $this->createMock(Client::class);

        $this->expectException(ResourceRequiresAuthentication::class);

        (new Campaigns($client, false))->getMyCampaignWithPledges();
    }
}
