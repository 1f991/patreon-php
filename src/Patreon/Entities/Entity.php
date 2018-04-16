<?php declare(strict_types=1);

namespace Squid\Patreon\Entities;

use Tightenco\Collect\Support\Collection;

abstract class Entity
{
    /**
     * The type of resource.
     *
     * @var string
     */
    protected $type;

    /**
     * The unique identifier of the entity.
     *
     * @var integer
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
     * @return void
     */
    public function __construct()
    {
        foreach ($this->relations as $relation) {
            $this->{$relation} = new Collection;
        }
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
     * Set the Entity Resource type.
     *
     * @param string $type Type of Resource
     *
     * @return void
     */
    public function setType(string $type): void
    {
        $this->type = $type;
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
     * Should this Entity be attached to relations?
     *
     * @return bool
     */
    public function shouldAttach(): bool
    {
        return true;
    }
}
