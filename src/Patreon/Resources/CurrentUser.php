<?php declare(strict_types=1);

namespace Squid\Patreon\Resources;

use Squid\Patreon\Entities\User;

class CurrentUser extends Resource
{
    /**
     * Get the current user.
     *
     * @return \Squid\Patreon\Entities\User
     */
    public function get(): User
    {
        return $this->hydrateDocument(
            $this->client->get('oauth2/api/current_user')->document()
        )->first();
    }
}
