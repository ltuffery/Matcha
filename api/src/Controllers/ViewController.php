<?php

namespace Matcha\Api\Controllers;

use Flight;
use Matcha\Api\Model\User;

class ViewController
{
    public function store(string $username)
    {
        if (Flight::user()->username == $username)
        {
            Flight::json([], 422);
            return;
        }

        $user = User::find([
            'username' => $username,
        ]);

        if (is_null($user)) {
            Flight::json([
                'message' => 'User not found',
            ], 404);

            return;
        }

        Flight::user()->view($user);
        Flight::json([], 204);
    }
}
