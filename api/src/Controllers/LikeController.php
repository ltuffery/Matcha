<?php

namespace Matcha\Api\Controllers;

use Flight;
use Matcha\Api\Model\User;

class LikeController
{
    public function store(string $username)
    {
        $target = User::find(['username' => $username]);
        $me = Flight::user();

        if (is_null($target) || $target->isBlocking($me)) {
            Flight::json(['message' => 'User not found'], 404)
            return;
        }

        Flight::user()->like($target);

        Flight::notifier()->publish("user/" . $target->id . "/notifications", [
            'type' => 'like',
            'message' => Flight::user()->username . " has liked your profile",
        ], private: false);

        Flight::json([], 201);
    }

    public function destroy(string $username)
    {
        $user = User::find([
            'username' => $username,
        ]);

        if (is_null($user)) {
            Flight::json([
                'message' => 'User not found',
            ], 404);

            return;
        }

        Flight::user()->unlike($user);
        Flight::json([], 203);
    }
}
