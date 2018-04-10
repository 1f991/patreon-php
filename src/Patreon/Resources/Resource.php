<?php declare(strict_types=1);

namespace Squid\Patreon\Resources;

use Squid\Patreon\Api\Client;
use Squid\Patreon\Entities\Campaign;
use Squid\Patreon\Entities\Goal;
use Squid\Patreon\Entities\Pledge;
use Squid\Patreon\Entities\Reward;
use Squid\Patreon\Entities\User;
use Squid\Patreon\Hydrator\EntityHydrator;
use Tightenco\Collect\Support\Collection;
use WoohooLabs\Yang\JsonApi\Response\JsonApiResponse;

abstract class Resource
{
    /**
    * Map Resources to Entities.
    *
    * @var array
    */
    const ENTITY_MAP = [
        'user' => User::class,
        'campaign' => Campaign::class,
        'reward' => Reward::class,
        'goal' => Goal::class,
        'pledge' => Pledge::class,
    ];

    /**
     * Constructs a new Resource.
     *
     * @param \Squid\Patreon\Api\Client $client Patreon API Client
     */
    public function __construct(Client $client)
    {
        $this->client = $client;
    }

    /**
     * Hydrate a JSON API Response
     *
     * @param \WoohooLabs\Yang\JsonApi\Response\JsonApiResponse $response Response
     *
     * @return \Tightenco\Collect\Support\Collection
     */
    protected function hydrateJsonApiResponse(JsonApiResponse $response): Collection
    {
        $hydrator = new EntityHydrator($response->document(), self::ENTITY_MAP);

        return $hydrator->hydrate();
    }
}
