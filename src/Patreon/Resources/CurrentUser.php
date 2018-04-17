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
        $this->onlyAvailableAuthenticated('get');

        return $this->getHydratedEntity(
            $this->buildUrl('current_user', User::class)
        );
    }
}
