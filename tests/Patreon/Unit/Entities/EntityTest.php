<?php declare(strict_types=1);

namespace Squid\Patreon\Tests\Unit\Entities;

use Squid\Patreon\Entities\Entity;
use Tightenco\Collect\Support\Collection;

class EntityTest extends TestCase
{
    public function testEntityRelationsAreInstantiatedAsCollections()
    {
        $entity = new ExampleEntity;

        $this->assertInstanceOf(Collection::class, $entity->pledges);
    }
}

class ExampleEntity extends Entity
{
    protected $relations = [
        'pledges',
        'rewards'
    ];
}
