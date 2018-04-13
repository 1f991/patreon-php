<?php declare(strict_types=1);

namespace Squid\Patreon\Resources;

use Squid\Patreon\Api\Client;
use Squid\Patreon\Entities\Address;
use Squid\Patreon\Entities\Campaign;
use Squid\Patreon\Entities\Card;
use Squid\Patreon\Entities\Entity;
use Squid\Patreon\Entities\Goal;
use Squid\Patreon\Entities\Pledge;
use Squid\Patreon\Entities\Reward;
use Squid\Patreon\Entities\User;
use Squid\Patreon\Exceptions\PatreonReturnedError;
use Squid\Patreon\Exceptions\ResourceRequiresAuthentication;
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
        'address' => Address::class,
        'campaign' => Campaign::class,
        'card' => Card::class,
        'goal' => Goal::class,
        'pledge' => Pledge::class,
        'reward' => Reward::class,
        'user' => User::class,
    ];

    /**
     * Should requests be made to the authenticated endpoint?
     *
     * @var bool
     */
    protected $authenticated = true;

    /**
     * Patreon Client used for retrieving data.
     *
     * @var \Squid\Patreon\Api\Client
     */
    protected $client;

    /**
     * Constructs a new Resource.
     *
     * @param \Squid\Patreon\Api\Client $client        Patreon API Client
     * @param bool                      $authenticated Make request to authenticated?
     */
    public function __construct(Client $client, bool $authenticated = true)
    {
        $this->client = $client;
        $this->authenticated = $authenticated;
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
            throw PatreonReturnedError::error(
                $document->error(0)->title(),
                $document->error(0)->detail()
            );
        }

        $hydrator = new EntityHydrator($document, self::ENTITY_MAP);

        return $hydrator->hydrate();
    }

    /**
     * Get the first hydrated Entity from the GET Request response Document.
     *
     * @param string $path Path to make the request to.
     *
     * @return \Squid\Patreon\Entities\Entity
     */
    public function getHydratedEntity(string $path): Entity
    {
        $document = $this->client->get($path, $this->authenticated)->document();

        return $this->hydrateDocument($document)->first();
    }

    /**
     * Throw an error if the resource requires authentication and the user has
     * disabled it for the requests.
     *
     * @param string $method Method that is only available when authenticated.
     *
     * @throws \Squid\Patreon\Exceptions\ResourceRequiresAuthentication
     *
     * @return void
     */
    protected function onlyAvailableAuthenticated(string $method): void
    {
        if (! $this->authenticated) {
            throw ResourceRequiresAuthentication::forMethod(
                get_class($this),
                $method
            );
        }
    }

    /**
     * Are requests made to the authenticated endpoint for this resource?
     *
     * @return bool
     */
    public function isAuthenticated(): bool
    {
        return $this->authenticated;
    }
}
