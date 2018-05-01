<?php declare(strict_types=1);

namespace Squid\Patreon\Tests\Unit\Entities;

use Squid\Patreon\Entities\Campaign;
use Squid\Patreon\Entities\Pledge;
use Squid\Patreon\Entities\User;
use Tightenco\Collect\Support\Collection;

class UserTest extends TestCase
{
    public function testUserEntityHasAllRequiredProperties()
    {
        $schema = [
            'about',
            'created',
            'discord_id',
            'email',
            'facebook',
            'facebook_id',
            'first_name',
            'full_name',
            'gender',
            'has_password',
            'image_url',
            'is_deleted',
            'is_emailed_verified',
            'is_nuked',
            'is_suspended',
            'last_name',
            'social_connections',
            'thumb_url',
            'twitch',
            'twitter',
            'url',
            'vanity',
            'youtube',
            // Relations
            'pledges',
            'campaign',
        ];

        $this->assertTrue($this->validateEntitySchema(new User, $schema));
    }

    public function testHasActivePledgeReturnsExpectedResults(): void
    {
        $activePledge = new Pledge;
        $activePledge->is_paused = false;

        $declinedPledge = new Pledge;
        $declinedPledge->declined_since = '2017-12-01T16:33:48+00:00';

        $activeUser = new User;
        $activeUser->pledge = $activePledge;

        $declinedUser = new User;
        $declinedUser->pledge = $declinedPledge;

        $noPledgeUser = new User;

        $this->assertTrue($activeUser->hasActivePledge());
        $this->assertFalse($declinedUser->hasActivePledge());
        $this->assertFalse($noPledgeUser->hasActivePledge());
    }

    public function testIsCreatorReturnsExpectedResults(): void
    {
        $creator = new User;
        $creator->campaign = new Campaign;

        $patron = new User;

        $this->assertTrue($creator->isCreator());
        $this->assertFalse($patron->isCreator());
    }

    public function testGetPledgeReturnsPledge(): void
    {
        $patron = new User;
        $patron->pledge = new Pledge;
        $user = new User;

        $this->assertInstanceOf(Pledge::class, $patron->getPledge());
        $this->assertNull($user->getPledge());
    }
}
