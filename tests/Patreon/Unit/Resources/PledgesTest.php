<?php declare(strict_types=1);

namespace Squid\Patreon\Tests\Unit\Resources;

use Squid\Patreon\Api\Client;
use Squid\Patreon\Entities\Pledge;
use Squid\Patreon\Exceptions\SortOptionsAreInvalid;
use Squid\Patreon\Resources\Pledges;
use Squid\Patreon\Tests\Unit\TestCase;
use Tightenco\Collect\Support\Collection;
use WoohooLabs\Yang\JsonApi\Schema\Document;
use WoohooLabs\Yang\JsonApi\Response\JsonApiResponse;

class PledgesTest extends TestCase
{
    public function testGetCampaignPledgesReturnsCollectionOfPledges(): void
    {
        $client = $this->createClientMockForResource('pledge', 5);

        $pledges = (new Pledges($client))->getCampaignPledges(1);

        $this->assertInstanceOf(Collection::class, $pledges);
        $this->assertCount(5, $pledges);

        $pledges->each(
            function ($pledge) {
                $this->assertInstanceOf(Pledge::class, $pledge);
            }
        );
    }

    public function testErrorIsThrownWhenInvalidSortIsProvided(): void
    {
        $client = $this->createMock(Client::class);

        $this->expectException(SortOptionsAreInvalid::class);

        $resource = new Pledges($client);
        $pledges = $resource->getPageOfCampaignPledges(1, 25, ['invalid-option']);
    }

    public function testGetPageOfCampaignPledgesCreatesValidRequestUrl(): void
    {
        $document = $this->createJsonApiMockWithDocumentForResource('pledge', 5);

        $path = 'campaigns/1/pledges';
        $path .= '?page%5Bcount%5D=25&sort=-updated';
        $path .= '&page%5Bcursor%5D=2017-12-02T15%3A21%3A20.121892%2B00%3A00';

        $attributes = implode(array_keys(get_object_vars(new Pledge)), ',');
        $path .= '&' . http_build_query(["fields[pledge]" => $attributes]);

        $client = $this->createMock(Client::class);
        $client->expects($this->once())
            ->method('get')
            ->with($path)
            ->willReturn($document);

        $resource = new Pledges($client);

        $pledges = $resource->getPageOfCampaignPledges(
            1,
            25,
            ['-updated'],
            '2017-12-02T15:21:20.121892+00:00'
        );
    }

    public function testGetCampaignPledgesPaginates()
    {
        $document1 = Document::createFromArray(
            [
            'data' => [
                ['type' => 'pledge', 'id' => '1'],
                ['type' => 'pledge', 'id' => '2'],
                ['type' => 'pledge', 'id' => '3'],
            ],
            'links' => [
                'next' => 'https://example.com/pledges?page=2'
            ]
            ]
        );

        $page1 = $this->createMock(JsonApiResponse::class);
        $page1->method('document')
            ->willReturn($document1);

        $document2 = Document::createFromArray(
            [
            'data' => [
                ['type' => 'pledge', 'id' => '4'],
                ['type' => 'pledge', 'id' => '5'],
            ],
            'links' => [
                'next' => 'https://example.com/pledges?page=3',
                'first' => 'https://example.com/pledges',
            ]
            ]
        );

        $page2 = $this->createMock(JsonApiResponse::class);
        $page2->method('document')
            ->willReturn($document2);

        $document3 = Document::createFromArray(
            [
            'data' => [
                ['type' => 'pledge', 'id' => '7'],
                ['type' => 'pledge', 'id' => '8'],
            ],
            'links' => [
                'first' => 'https://example.com/pledges',
            ]
            ]
        );

        $page3 = $this->createMock(JsonApiResponse::class);
        $page3->method('document')
            ->willReturn($document3);


        $client = $this->createMock(Client::class);
        $client->expects($this->exactly(3))
            ->method('get')
            ->willReturnOnConsecutiveCalls($page1, $page2, $page3);

        $resource = new Pledges($client);

        $pledges = $resource->getCampaignPledges(1);

        $this->assertInstanceOf(Collection::class, $pledges);
        $this->assertCount(7, $pledges);
    }
}
