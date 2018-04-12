<?php declare(strict_types=1);

namespace Squid\Patreon;

use Http\Discovery\HttpClientDiscovery;
use Squid\Patreon\Api\Client;
use Squid\Patreon\Resources\Campaigns;
use Squid\Patreon\Resources\CurrentUser;
use Squid\Patreon\Resources\Pledges;
use Squid\Patreon\Resources\Resource;
use Squid\Patreon\Resources\Webhook;

class Patreon
{
    /**
     * The Patreon API Client instance.
     *
     * @var \Squid\Patreon\Api\Client
     */
    protected $client;

    /**
     * Should requests be made to the authenticated endpoint?
     *
     * @var bool
     */
    protected $authenticated = true;

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
     * @return \Squid\Patreon\Resources\CurrentUser
     */
    public function me(): CurrentUser
    {
        return $this->newResource(CurrentUser::class);
    }

    /**
     * Get the Campaigns Resource.
     *
     * @return \Squid\Patreon\Resources\Campaigns
     */
    public function campaigns(): Campaigns
    {
        return $this->newResource(Campaigns::class);
    }

    /**
     * Get the Pledges Resource.
     *
     * @return \Squid\Patreon\Resources\Pledges
     */
    public function pledges(): Pledges
    {
        return $this->newResource(Pledges::class);
    }

    /**
     * Get the Webhook Resource.
     *
     * @return \Squid\Patreon\Resources\Webhook
     */
    public function webhook(): Webhook
    {
        return $this->newResource(Webhook::class);
    }

    /**
     * Creates a new Resource object.
     *
     * @param string $resource Resource to create
     *
     * @return \Squid\Patreon\Resources\Resource
     */
    protected function newResource(string $resource): Resource
    {
        return new $resource($this->client, $this->authenticated);
    }

    /**
     * Set the client status to unauthenticated so that all requests made are to
     * the unauthenticated endpoint.
     *
     * @return void
     */
    public function useUnauthenticatedResources(): void
    {
        $this->authenticated = false;
    }
}
