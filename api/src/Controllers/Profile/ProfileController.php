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

    public function update(string $username): void
    {
        /** @var User $user */
        $user = Flight::user();

        if ($user->username !== $username) {
            Flight::json([
                'message' => 'Forbidden',
            ], 403);
            return;
        }

        foreach (Flight::request()->data as $key => $value) {
            if (!isset($user->{$key})) {
                continue;
            }

            $user->{$key} = $value;
        }

        $user->save();
    }
}
