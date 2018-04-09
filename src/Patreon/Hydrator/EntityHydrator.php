<?php declare(strict_types=1);

namespace Squid\Patreon\Hydrator;

use UnexpectedValueException;
use Squid\Patreon\Entities\Entity;
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
        $parent = $this->newEntityOfType($resource->type());

        foreach (get_object_vars($parent) as $attribute => $default) {
            $parent->{$attribute} = $resource->attribute($attribute);
        }

        $this->saveEntityToCollection($parent);

        foreach ($resource->relationships() as $name => $relationship) {
            foreach ($relationship->resourceLinks() as $link) {
                $this->attachRelatedEntity(
                    $parent,
                    $link['type'],
                    $link['id'],
                    $relationship
                );
            }
        }

        return $parent;
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
        $entity = $this->getEntityFromCollection($type, $id);

        if ($entity === null && $this->document->hasIncludedResource($type, $id)) {
            $entity = $this->hydrateResource(
                $this->document->resource($type, $id)
            );
        }

        if ($entity === null) {
            return;
        }

        if ($relationship->isToOneRelationship()) {
            $parent->{$relationship->name()} = $entity;
        } else {
            $parent->{$relationship->name()}[] = $entity;
        }
    }

    /**
     * Creates a new Entity for the Resource.
     *
     * @param string $type Type of Entity to create
     *
     * @return \Squid\Patreon\Entities\Entity
     */
    protected function newEntityOfType(string $type): Entity
    {
        if (! array_key_exists($type, $this->resourceEntityMap)) {
            throw new UnexpectedValueException(
                "Missing Entity class for {$type} resources."
            );
        }

        return new $this->resourceEntityMap[$type];
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
