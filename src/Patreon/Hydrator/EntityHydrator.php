<?php declare(strict_types=1);

namespace Squid\Patreon\Hydrator;

use Squid\Patreon\Entities\Entity;
use Squid\Patreon\Exceptions\ResourceHasNoEntity;
use Tightenco\Collect\Support\Collection;
use WoohooLabs\Yang\JsonApi\Schema\Document;
use WoohooLabs\Yang\JsonApi\Schema\Relationship;
use WoohooLabs\Yang\JsonApi\Schema\ResourceObject;

class EntityHydrator
{
    /**
     * Document from which to source the entities.
     *
     * @var \WoohooLabs\Yang\JsonApi\Schema\Document
     */
    protected $document;

    /**
     * Map of resource type to Entity.
     *
     * @var array
     */
    protected $resourceEntityMap;

    /**
     * Collection of hydrated Resources.
     *
     * @var array
     */
    protected $resourceCollection;

    /**
     * Constructs a new Entity Hydrator.
     *
     * @param Document $document          Document from which to source the entities.
     * @param array    $resourceEntityMap Map resources to entities.
     *
     * @return void
     */
    public function __construct(Document $document, array $resourceEntityMap)
    {
        $this->document = $document;
        $this->resourceEntityMap = $resourceEntityMap;
    }

    /**
     * Returns a Collection of Entities hydrated from all of the Resources
     * in the document, with all related resources hydrated.
     *
     * @return \Tightenco\Collect\Support\Collection
     */
    public function hydrate(): Collection
    {
        $entities = new Collection;

        foreach ($this->document->primaryResources() as $resource) {
            $entities->push($this->hydrateResource($resource));
        }

        return $entities;
    }

    /**
     * Hydrates an Entity from a ResourceObject.
     *
     * @param ResourceObject $resource Resource to be hydrated
     *
     * @return \Squid\Patreon\Entities\Entity
     */
    protected function hydrateResource(ResourceObject $resource): Entity
    {
        $entity = $this->newEntityOfType(
            $resource->type(),
            $resource->id(),
            $resource->attributes()
        );

        $this->saveEntityToCollection($entity);

        foreach ($resource->relationships() as $name => $relationship) {
            foreach ($relationship->resourceLinks() as $link) {
                $this->attachRelatedEntity(
                    $entity,
                    $link['type'],
                    $link['id'],
                    $relationship
                );
            }
        }

        $entity->postProcess();

        return $entity;
    }

    /**
     * Attach a related Entity.
     *
     * @param Entity       $parent       Entity to attach to
     * @param string       $type         Type of Resource
     * @param string       $id           ID of the Resource
     * @param Relationship $relationship Relationship
     *
     * @return \Squid\Patreon\Entities\Entity
     */
    protected function attachRelatedEntity(
        Entity &$parent,
        string $type,
        string $id,
        Relationship $relationship
    ): void {
        $entity = $this->getEntity($type, $id);

        if ($entity === null || ! $entity->shouldAttach()) {
            return;
        }

        if ($relationship->isToOneRelationship()) {
            $parent->{$relationship->name()} = $entity;
        } else {
            $parent->{$relationship->name()}->push($entity);
        }
    }

    /**
     * Get an Entity: collection takes priority, fallback to the document.
     *
     * @param string  $type Type of Resource
     * @param integer $id   ID of Resource
     *
     * @return \Squid\Patreon\Entities\Entity|null
     */
    protected function getEntity(string $type, string $id): ?Entity
    {
        $entity = $this->getEntityFromCollection($type, $id);

        if ($entity === null && $this->document->hasIncludedResource($type, $id)) {
            $entity = $this->hydrateResource(
                $this->document->resource($type, $id)
            );
        }

        return $entity;
    }

    /**
     * Creates a new Entity for the Resource.
     *
     * @param string $type       Type of Entity to create
     * @param string $id         ID of the Entity
     * @param array  $properties Properties of the Entity
     *
     * @throws \Squid\Patreon\Exceptions\ResourceHasNoEntity
     *
     * @return \Squid\Patreon\Entities\Entity
     */
    protected function newEntityOfType(
        string $type,
        string $id,
        array $properties
    ): Entity {
        if (! array_key_exists($type, $this->resourceEntityMap)) {
            throw ResourceHasNoEntity::forResource($type);
        }

        return new $this->resourceEntityMap[$type]($id, $properties);
    }

    /**
     * Gets Entity by key from the Resource collection.
     *
     * @param string  $type Type of Resource
     * @param integer $id   ID of Resource
     *
     * @return \Squid\Patreon\Entities\Entity|null
     */
    protected function getEntityFromCollection(string $type, string $id): ?Entity
    {
        return $this->resourceCollection["{$type}-{$id}"] ?? null;
    }

    /**
     * Saves Entity to the Resource collection.
     *
     * @param Entity $entity Entity to save
     *
     * @return void
     */
    protected function saveEntityToCollection(Entity $entity): void
    {
        $this->resourceCollection[$entity->getEntityKey()] = $entity;
    }
}
