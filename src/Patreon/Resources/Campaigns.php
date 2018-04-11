<?php declare(strict_types=1);

namespace Squid\Patreon\Resources;

use Squid\Patreon\Entities\Campaign;

class Campaigns extends Resource
{
    /**
     * Get the current users Campaign.
     *
     * @return \Squid\Patreon\Entities\Campaign
     */
    public function getMyCampaign(): Campaign
    {
        return $this->hydrateDocument(
            $this->client->get('oauth2/api/current_user/campaigns')->document()
        )->first();
    }

    /**
     * Get the current users Campaign with Pledges.
     *
     * @return \Squid\Patreon\Entities\Campaign
     */
    public function getMyCampaignWithPledges(): Campaign
    {
        $campaign = $this->hydrateDocument(
            $this->client->get('oauth2/api/current_user/campaigns')->document()
        )->first();

        return $this->attachPledges($campaign);
    }

    /**
     * Get a Campaign by ID.
     *
     * @param integer $id Campaign ID
     *
     * @return \Squid\Patreon\Entities\Campaign
     */
    public function getCampaign(int $id): Campaign
    {
        return $this->hydrateDocument(
            $this->client->get("campaigns/{$id}")->document()
        )->first();
    }

    /**
     * Get a Campaign by ID with Pledges.
     *
     * @param integer $id Campaign ID
     *
     * @return \Squid\Patreon\Entities\Campaign
     */
    public function getCampaignWithPledges(int $id): Campaign
    {
        $campaign = $this->hydrateDocument(
            $this->client->get("campaigns/{$id}")->document()
        )->first();

        return $this->attachPledges($campaign);
    }

    /**
     * Retrieves and attaches pledges for a campaign to a Campaign object.
     *
     * @param Campaign $campaign Campaign entity to fetch pledges for and attach to
     *
     * @return \Squid\Patreon\Entities\Campaign
     */
    protected function attachPledges($campaign): Campaign
    {
        $pledges = (new Pledges($this->client))->getCampaignPledges($campaign->id);

        $campaign->pledges = $pledges;

        return $campaign;
    }
}
