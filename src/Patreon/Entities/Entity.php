<?php declare(strict_types=1);

namespace Squid\Patreon\Entities;

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
}
