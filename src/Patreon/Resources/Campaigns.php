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
        $this->onlyAvailableAuthenticated();

        return $this->getHydratedEntity('current_user/campaigns');
    }

    /**
     * Get the current users Campaign with Pledges.
     *
     * @return \Squid\Patreon\Entities\Campaign
     */
    public function getMyCampaignWithPledges(): Campaign
    {
        $this->onlyAvailableAuthenticated();

        $campaign = $this->getHydratedEntity('current_user/campaigns');

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
        return $this->getHydratedEntity("campaigns/{$id}");
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
        $campaign = $this->getHydratedEntity("campaigns/{$id}");

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
        $resource = (new Pledges($this->client, $this->authenticated));
        $pledges = $resource->getCampaignPledges($campaign->id);

        $campaign->pledges = $pledges;

        return $campaign;
    }
}
