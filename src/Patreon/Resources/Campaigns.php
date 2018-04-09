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
        return $this->hydrateJsonApiResponse(
            $this->client->get('current_user/campaigns')
        )->first();
    }
}
