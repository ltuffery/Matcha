<?php

namespace Matcha\Api\Controllers\Profile;

use Flight;
use Matcha\Api\Model\User;
use Matcha\Api\Resources\ProfileResource;

class ProfileController
{
    public function index(): void
    {
        Flight::json(
            new ProfileResource(Flight::user())
        );
    }

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

        foreach (Flight::request()->data as $key => $value) {
            if (!isset($user->{$key})) {
                continue;
            }

            $user->{$key} = $value;
        }

        $user->save();
    }

    public function destroy(string $username): void
    {
        Flight::user()->delete();

        Flight::json([], 203);
    }
}
