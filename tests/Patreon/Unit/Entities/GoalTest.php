<?php declare(strict_types=1);

namespace Squid\Patreon\Tests\Unit\Entities;

use Squid\Patreon\Entities\Goal;

class GoalTest extends TestCase
{
    public function testGoalEntityHasAllRequiredProperties()
    {
        $schema = [
            'amount_cents',
            'completed_percentage',
            'created_at',
            'description',
            'reached_at',
            'title',
        ];

        $this->assertTrue($this->validateEntitySchema(new Goal, $schema));
    }

    public function testIsCompleteReturnsExpectedResults(): void
    {
        $complete = new Goal;
        $complete->reached_at = '2018-02-21T16:33:33.974297+00:00';
        $complete->completed_percentage = 100;

        $incomplete = new Goal;
        $incomplete->reached_at = null;
        $incomplete->completed_percentage = 80;

        $completed = new Goal;
        $completed->reached_at = '2018-02-21T16:33:33.974297+00:00';
        $completed->completed_percentage = 80;

        $this->assertTrue($complete->isComplete());
        $this->assertFalse($incomplete->isComplete());
        $this->assertFalse($completed->isComplete());
    }

    public function testHasBeenCompletedReturnsExpectedResults(): void
    {
        $completed = new Goal;
        $completed->reached_at = '2018-02-21T16:33:33.974297+00:00';
        $completed->completed_percentage = 80;

        $this->assertTrue($completed->hasBeenCompleted());
    }
}
