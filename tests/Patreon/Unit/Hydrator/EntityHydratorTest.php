<?php declare(strict_types=1);

namespace Squid\Patreon\Tests\Unit\Hydrator;

use Squid\Patreon\Entities\Entity;
use Squid\Patreon\Exceptions\ResourceHasNoEntity;
use Squid\Patreon\Tests\Unit\TestCase;
use Squid\Patreon\Hydrator\EntityHydrator;
use Tightenco\Collect\Support\Collection;
use WoohooLabs\Yang\JsonApi\Schema\Document;

class EntityHydratorTest extends TestCase
{
    public function testDocumentHydratorReturnsCollectionOfEntities(): void
    {
        $document = Document::createFromArray(
            json_decode($this->fixture('jsonapi/articles.json'), true)
        );

        $hydrator = new EntityHydrator(
            $document,
            [
            'article' => ArticleEntity::class,
            'people' => PersonEntity::class
            ]
        );

        $entities = $hydrator->hydrate();

        $this->assertInstanceOf(Collection::class, $entities);
        $this->assertCount(2, $entities);

        $entities->each(
            function ($entity) {
                $this->assertInstanceOf(Entity::class, $entity);
            }
        );
    }

    public function testEntityIsHydratedWithRelationshipsFromJsonApiDocument(): void
    {
        $document = Document::createFromArray(
            json_decode($this->fixture('jsonapi/article.json'), true)
        );

        $hydrator = new EntityHydrator(
            $document,
            [
            'article' => ArticleEntity::class,
            'people' => PersonEntity::class
            ]
        );

        $article = $hydrator->hydrate()->first();

        $this->assertEquals('JSON API paints my bikeshed!', $article->title);
        $this->assertEquals('John', $article->author->name);
    }

    public function testExceptionIsThrownWhenEntityDoesNotExistForResourceType()
    {
        $document = Document::createFromArray(
            json_decode($this->fixture('jsonapi/article.json'), true)
        );

        $hydrator = new EntityHydrator(
            $document,
            [
            'article' => ArticleEntity::class
            ]
        );

        $this->expectException(ResourceHasNoEntity::class);
        $hydrator->hydrate($document);
    }
}

class ArticleEntity extends Entity
{
    public $title;
}

class PersonEntity extends Entity
{
    public $name;
}
