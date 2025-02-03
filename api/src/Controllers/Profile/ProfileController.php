<?php

namespace Matcha\Api\Controllers\Profile;

use Flight;
use Matcha\Api\Model\User;
use Matcha\Api\Resources\ProfileResource;

class ProfileController
{
    /**
     * @throws \ReflectionException
     */
    public function show(string $username): void
    {
        Flight::json(
            new ProfileResource(User::find(["username" => $username]))
        );
    }
}
