<?php declare(strict_types=1);

namespace Squid\Patreon\Hydrator;

use UnexpectedValueException;
use Squid\Patreon\Entities\Entity;
use WoohooLabs\Yang\JsonApi\Schema\Document;
use WoohooLabs\Yang\JsonApi\Schema\ResourceObject;

class EntityHydrator
{
    /**
     * Map of resource type to Entity.
     *
     * @var array
     */
    protected $resourceEntityMap;

    /**
     * Constructs a new Entity Hydrator.
     *
     * @param array $resourceEntityMap Map resources to entities.
     *
     * @return void
     */
    public function __construct(array $resourceEntityMap)
    {
        $this->resourceEntityMap = $resourceEntityMap;
    }

    /**
     * Hydrates a Document's primary Entity with attributes and relationships.
     *
     * @param Document $document JSON API Document
     *
     * @return \Squid\Patreon\Entities\Entity
     */
    public function hydrate(Document $document): Entity
    {
        $resourceMap = [];

        return $this->hydrateResource(
            $document->primaryResource(),
            $document,
            $resourceMap
        );
    }

    /**
     * Hydrates an Entity from a ResourceObject.
     *
     * @param ResourceObject $resource    Resource to be hydrated
     * @param Document       $document    Document that the Resource belongs to
     * @param array          $resourceMap Resources that have already been hydrated
     *
     * @return \Squid\Patreon\Entities\Entity
     */
    protected function hydrateResource(
        ResourceObject $resource,
        Document $document,
        array &$resourceMap
    ): Entity {
        $entity = $this->newEntityOfType($resource->type());

        foreach (get_object_vars($entity) as $attribute => $default) {
            $entity->{$attribute} = $resource->attribute($attribute);
        }

        $this->saveEntityToMap($entity, $resourceMap);

        foreach ($resource->relationships() as $name => $relationship) {
            foreach ($relationship->resourceLinks() as $link) {
                $related = $this->getEntityFromMap($link['type'], $link['id'], $resourceMap);

                if ($related === null && $document->hasIncludedResource($link['type'], $link['id'])) {
                    $relatedResource = $document->resource($link['type'], $link['id']);
                    $related = $this->hydrateResource($relatedResource, $document, $resourceMap);
                }

                if ($related === null) {
                    continue;
                }

                if ($relationship->isToOneRelationship()) {
                    $entity->{$name} = $related;
                } else {
                    $entity->{$name}[] = $related;
                }
            }
        }

        return $entity;
    }

    /**
     * Creates a new Entity for the resource.
     *
     * @param string $type Type of Entity to create
     *
     * @return \Squid\Patreon\Entities\Entity
     */
    protected function newEntityOfType(string $type): Entity
    {
        if (! array_key_exists($type, $this->resourceEntityMap)) {
            throw new UnexpectedValueException("Entity class has not been specified for {$type} resources.");
        }

        return new $this->resourceEntityMap[$type];
    }

    /**
     * Gets Entity by key from the resource map if it exists.
     *
     * @param string  $type        Type of Entity
     * @param integer $id          ID of Entity
     * @param array   $resourceMap Map to look for the Entity in
     *
     * @return \Squid\Patreon\Entities\Entity|null
     */
    protected function getEntityFromMap(
        string $type,
        string $id,
        array $resourceMap
    ): ?Entity {
        return $resourceMap["{$type}-{$id}"] ?? null;
    }

    /**
     * Saves Entity to the resource map.
     *
     * @param Entity $entity      Entity to save
     * @param array  $resourceMap Map to save the Entity to
     *
     * @return void
     */
    protected function saveEntityToMap(Entity $entity, array &$resourceMap): void
    {
        $resourceMap[$entity->getEntityKey()] = $entity;
    }
}
