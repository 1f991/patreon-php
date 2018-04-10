<?php declare(strict_types=1);

namespace Squid\Patreon;

use Http\Discovery\HttpClientDiscovery;
use Squid\Patreon\Api\Client;
use Squid\Patreon\Resources\Campaigns;
use Squid\Patreon\Resources\CurrentUser;
use Squid\Patreon\Resources\Resource;

class Patreon
{
    /**
     * The Patreon API Client instance.
     *
     * @var \Squid\Patreon\Api\Client
     */
    protected $client;

    /**
     * Create a new Patreon instance.
     *
     * @param string $accessToken A Patreon API oauth2 access token
     * @param Client $client      Patreon API Client
     *
     * @return void
     */
    public function __construct(string $accessToken, ?Client $client = null)
    {
        $this->client = $client ?: new Client;
        $this->client->setAccessToken($accessToken);
    }

    /**
     * Get the Current User Resource.
     *
     * @return \Squid\Patreon\Resources\Resource
     */
    public function me(): Resource
    {
        return new CurrentUser($this->client);
    }

    /**
     * Get the Campaigns Resource.
     *
     * @return \Squid\Patreon\Resources\Campaigns
     */
    public function campaigns(): Campaigns
    {
        return new Campaigns($this->client);
    }

    /**
     * Get the Pledges Resource.
     *
     * @return \Squid\Patreon\Resources\Pledges
     */
    public function pledges(): Pledges
    {
        return new Pledges($this->client);
    }
}
