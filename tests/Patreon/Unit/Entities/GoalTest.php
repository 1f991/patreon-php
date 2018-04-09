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
}
