<?php declare(strict_types=1);

namespace Squid\Patreon\Entities;

class Goal extends Entity
{
    /**
     * Resource type (from Patreon).
     *
     * @var string
     */
    protected $type = 'goal';

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

    /**
     * Has the Goal been completed at some point in the past?
     * Note: A Goal is completed once, if the completed percentage drops below 100
     * it will remain completed, if you need to test if a goal is currently
     * complete then use `isComplete()`.
     *
     * @return bool
     */
    public function hasBeenCompleted(): bool
    {
        return $this->reached_at !== null;
    }

    /**
     * Is the Goal currently complete?
     *
     * @return bool
     */
    public function isComplete(): bool
    {
        return $this->reached_at !== null && $this->completed_percentage === 100;
    }
}
