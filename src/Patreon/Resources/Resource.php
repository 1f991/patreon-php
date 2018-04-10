<?php declare(strict_types=1);

namespace Squid\Patreon\Resources;

use Exception;
use Squid\Patreon\Api\Client;
use Squid\Patreon\Entities\Address;
use Squid\Patreon\Entities\Campaign;
use Squid\Patreon\Entities\Goal;
use Squid\Patreon\Entities\Pledge;
use Squid\Patreon\Entities\Reward;
use Squid\Patreon\Entities\User;
use Squid\Patreon\Hydrator\EntityHydrator;
use Tightenco\Collect\Support\Collection;
use WoohooLabs\Yang\JsonApi\Schema\Document;

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
        'address' => Address::class,
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
     * Hydrate a Document.
     *
     * @param \WoohooLabs\Yang\JsonApi\Schema\Document $document Document to hydrate
     *
     * @return \Tightenco\Collect\Support\Collection
     */
    protected function hydrateDocument(Document $document): Collection
    {
        if ($document->hasErrors()) {
            $error = $document->error(0);

            throw new Exception("{$error->title()} {$error->detail()}");
        }

        $hydrator = new EntityHydrator($document, self::ENTITY_MAP);

        return $hydrator->hydrate();
    }
}
