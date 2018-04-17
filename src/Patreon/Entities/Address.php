<?php declare(strict_types=1);

namespace Squid\Patreon\Entities;

class Address extends Entity
{
    /**
     * Resource type (from Patreon).
     *
     * @var string
     */
    protected $type = 'address';

    /**
     * Name of the person to deliver to at this address.
     * Example: John Doe
     *
     * @var
     */
    public $addressee;

    /**
     * Name of the city.
     * Example: London
     *
     * @var string
     */
    public $city;

    /**
     * ISO 3166-1 Alpha-2 Code representing the country.
     * Example: GB
     *
     * @var string
     */
    public $country;

    /**
     * First line of the street address.
     * Example: Aldgate Tower
     *
     * @var string
     */
    public $line_1;

    /**
     * Second line of the street address.
     * Example: 2 Leman Street
     *
     * @var string
     */
    public $line_2;

    /**
     * Postal / Zip Code of the street address.
     * Example: E1 8FA
     *
     * @var string
     */
    public $postal_code;

    /**
     * ANSI standard INCITS 38:2009 US State code if address is in the United
     * States.
     * Example: CA
     *
     * @var string
     */
    public $state;
}
