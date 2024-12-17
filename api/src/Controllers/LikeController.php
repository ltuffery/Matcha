<?php

namespace Matcha\Api\Controllers;

use Flight;
use Matcha\Api\Model\User;

class LikeController
{
    public function store(string $username)
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

        Flight::user()->like($user);
        Flight::json([], 203);
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
