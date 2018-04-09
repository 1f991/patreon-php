<?php declare(strict_types=1);

namespace Squid\Patreon\Tests\Unit\Entities;

use Squid\Patreon\Entities\User;

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
        ];

        $this->assertTrue($this->validateEntitySchema(new User, $schema));
    }
}
