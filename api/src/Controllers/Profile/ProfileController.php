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
        $userInfo = (new ProfileResource(User::find(["username" => $username])))->jsonSerialize();
        $userInfo["distance"] = 24;
        $userInfo["me"] = $username == Flight::user()->username;

        Flight::json($userInfo);
    }
}