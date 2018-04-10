<?php declare(strict_types=1);

namespace Squid\Patreon\Resources;

use Squid\Patreon\Entities\Pledge;
use Tightenco\Collect\Support\Collection;
use UnexpectedValueException;

class Pledges extends Resource
{
    /**
    * Valid sort options (dash prefix means descending order).
    *
    * @var array
    */
    const SORT_OPTIONS = [
        'created',
        '-created',
        'updated',
        '-updated'
    ];

    /**
     * Get all of the Pledges for a Campaign.
     *
     * @param integer $campaign Campaign ID
     *
     * @return \Tightenco\Collect\Support\Collection
     */
    public function getCampaignPledges(int $campaign): Collection
    {
        $pledges = new Collection;
        $filter = '';

        while (true) {
            $page = $this->client->get("campaigns/{$campaign}/pledges?" . $filter);

            $pledges = $pledges->merge(
                $this->hydrateJsonApiResponse($page)
            );

            if (! $next = $page->document()->links()->link('next')) {
                break;
            }

            $filter = parse_url($next->href(), PHP_URL_QUERY);
        }

        return $pledges;
    }

    /**
     * Get a page of Pledges for a Campaign. Learn more about pagination in the
     * Patreon API documentation: https://docs.patreon.com/#pagination-and-sorting
     *
     * @param int    $campaign Campaign ID
     * @param int    $count    Amount of results
     * @param array  $sort     Attributes to sort by (ordered by priority)
     * @param string $cursor   Attribute value to use to identify previous result
     *
     * @return \Tightenco\Collect\Support\Collection
     */
    public function getPageOfCampaignPledges(
        int $campaign,
        int $count = 10,
        array $sort = [],
        string $cursor = null
    ): Collection {
        if ($attributes = array_diff($sort, self::SORT_OPTIONS)) {
            throw new UnexpectedValueException(
                "Invalid sort options: " . implode(', ', $attributes)
            );
        }

        $parameters = http_build_query(
            [
            'page[count]' => $count,
            'sort' => implode(',', $sort) ?: null,
            'page[cursor]' => $cursor
            ]
        );

        return $this->hydrateJsonApiResponse(
            $this->client->get("campaigns/{$campaign}/pledges?" . $parameters)
        );
    }
}
