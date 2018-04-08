<?php declare(strict_types=1);

namespace Squid\Patreon\Tests\Unit\Hydrator;

use Squid\Patreon\Tests\Unit\TestCase;
use Squid\Patreon\Entities\Entity;
use Squid\Patreon\Hydrator\EntityHydrator;
use WoohooLabs\Yang\JsonApi\Schema\Document;
use UnexpectedValueException;

class EntityHydratorTest extends TestCase
{

    public function testEntityIsHydratedWithRelationshipsFromJsonApiDocument(): void
    {
        $document = Document::createFromArray(
            json_decode($this->fixture('jsonapi/article.json'), true)
        );

        $hydrator = new EntityHydrator(
            $document,
            [
            'articles' => ArticleEntity::class,
            'people' => PersonEntity::class
            ]
        );

        $article = $hydrator->hydrate();

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
            'articles' => ArticleEntity::class
            ]
        );

        $this->expectException(UnexpectedValueException::class);
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
