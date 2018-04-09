<?php declare(strict_types=1);

namespace Squid\Patreon\Tests\Unit\Entities;

use DomainException;
use Squid\Patreon\Tests\Unit\TestCase as App_TestCase;

abstract class TestCase extends App_TestCase
{
    /**
     * Validates that the entity has all of the properties specified in the schema.
     *
     * @param \Squid\Patreon\Entities\Entity $entity Entity to validate
     * @param array                          $schema Required schema
     */
    public function validateEntitySchema($entity, $schema)
    {
        foreach ($schema as $field) {
            if (! property_exists($entity, $field)) {
                $name = get_class($entity);
                throw new DomainException("{$name} is missing field {$field}.");
            }
        }

        return true;
    }
}
