<?php declare(strict_types=1);

namespace Squid\Patreon\Entities;

class Goal extends Entity
{
    /**
     * Amount in cents at which this goal is achieved.
     *
     * @var integer
     */
    public $amount_cents;

    /**
     * Percentage of the goals target achieved.
     *
     * @var integer
     */
    public $completed_percentage;

    /**
     * Timestamp of the goal creation, ISO 8601 combined date and time in UTC.
     * Example: "2017-12-01T16:33:48+00:00"
     *
     * @var string
     */
    public $created_at;

    /**
     * Description of the goal.
     * Example: "I can work on creating content full time."
     *
     * @var string
     */
    public $description;

    /**
     * Timestamp of the first date that the goal was reached at, ISO 8601
     * combined date and time in UTC.
     * Example: "2017-12-01T16:33:48+00:00"
     *
     * @var string
     */
    public $reached_at;

    /**
     * Title of the goal.
     * Example: "Pay my rent"
     *
     * @var string
     */
    public $title;
}
