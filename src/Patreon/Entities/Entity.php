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
}
