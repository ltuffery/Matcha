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
        $user = User::find(["username" => $username]);

        if (is_null($user)) {
            Flight::json(["message" => "Not found."], 404);
            return;
        }

        Flight::json(
            new ProfileResource($user)
        );
    }
}
