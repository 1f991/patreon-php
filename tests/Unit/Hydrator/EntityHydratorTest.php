<?php declare(strict_types=1);

namespace Squid\Patreon\Tests\Unit\Hydrator;

use PHPUnit\Framework\TestCase;
use Squid\Patreon\Entities\Entity;
use Squid\Patreon\Hydrator\EntityHydrator;
use WoohooLabs\Yang\JsonApi\Schema\Document;
use UnexpectedValueException;

class EntityHydratorTest extends TestCase
{

    public function testEntityIsHydratedWithRelationshipsFromJsonApiDocument(): void
    {
        $document = Document::createFromArray(
            json_decode('{"data":[{"type":"articles","id":"1","attributes":{"title":"JSON API paints my bikeshed!","body":"The shortest article. Ever.","created":"2015-05-22T14:56:29.000Z","updated":"2015-05-22T14:56:28.000Z"},"relationships":{"author":{"data":{"id":"42","type":"people"}}}}],"included":[{"type":"people","id":"42","attributes":{"name":"John","age":80,"gender":"male"}}]}', true)
        );

        $hydrator = new EntityHydrator(
            [
            'articles' => ArticleEntity::class,
            'people' => PersonEntity::class
            ]
        );

        $article = $hydrator->hydrate($document);

        $this->assertEquals('JSON API paints my bikeshed!', $article->title);
        $this->assertEquals('John', $article->author->name);
    }

    public function testExceptionIsThrownWhenEntityDoesNotExistForResourceType()
    {
        $document = Document::createFromArray(
            json_decode('{"data":[{"type":"articles","id":"1","attributes":{"title":"JSON API paints my bikeshed!","body":"The shortest article. Ever.","created":"2015-05-22T14:56:29.000Z","updated":"2015-05-22T14:56:28.000Z"},"relationships":{"author":{"data":{"id":"42","type":"people"}}}}],"included":[{"type":"people","id":"42","attributes":{"name":"John","age":80,"gender":"male"}}]}', true)
        );

        $hydrator = new EntityHydrator(
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
