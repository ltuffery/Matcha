<?php

namespace Matcha\Api\Controllers;

use Flight;
use Matcha\Api\Model\User;

class PassController
{
    public function store(string $username)
    {
        $target = User::find(['username' => $username]);

        if (is_null($target)) {
            Flight::json(['message' => 'User not found'], 404);
            return;
        }

        Flight::user()->pass($target);

        Flight::json([], 204);
    }
}
