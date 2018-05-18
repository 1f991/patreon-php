<?php

declare(strict_types=1);

namespace Squid\Patreon\Entities;

use Tightenco\Collect\Support\Collection;

abstract class Entity
{
    /**
     * Base URL for absolute Patreon URLs.
     *
     * @var string
     */
    const PATREON_URL = 'https://patreon.com';

    /**
     * The type of resource.
     *
     * @var string
     */
    protected $type;

    /**
     * The unique identifier of the entity.
     *
     * @var int
     */
    public $id;

    /**
     * Relations that should be initialized as empty Collections.
     *
     * @var array
     */
    protected $relations = [];

    /**
     * Instantiate a new Entity with the relations as empty Collections.
     *
     * @param string $id         ID of the Entity
     * @param array  $properties Properties of the Entity
     *
     * @return void
     */
    public function __construct(string $id = '', array $properties = [])
    {
        $this->createRelations();
        $this->fillProperties($properties);
        $this->setId($id);
    }

    /**
     * Get the unique key for the entity.
     *
     * @return string
     */
    public function getEntityKey(): string
    {
        return "{$this->type}-{$this->id}";
    }

    /**
     * Set the Entity ID.
     *
     * @param string $id ID of the Entity.
     *
     * @return void
     */
    public function setId(string $id): void
    {
        $this->id = (int) $id;
    }

    /**
     * Get the Entity Resource Type.
     *
     * @return string
     */
    public function getType(): string
    {
        return $this->type;
    }

    /**
     * Should this Entity be attached to relations?
     *
     * @return bool
     */
    public function shouldAttach(): bool
    {
        return true;
    }

    /**
     * Fill properties with values from array.
     *
     * @param array $properties array of properties and their values.
     *
     * @return void
     */
    protected function fillProperties(array $properties)
    {
        foreach ($properties as $key => $value) {
            if (!property_exists($this, $key) || isset($this->relations[$key])) {
                continue;
            }

            $this->{$key} = $value;
        }
    }

    /**
     * Create relation Collections.
     *
     * @return void
     */
    protected function createRelations()
    {
        foreach ($this->relations as $relation) {
            $this->{$relation} = new Collection();
        }
    }

    /**
     * After an Entity has been assembled with all relations, it may need to be
     * post processed to assemble data.
     *
     * @return void
     */
    public function postProcess()
    {
    }
}
