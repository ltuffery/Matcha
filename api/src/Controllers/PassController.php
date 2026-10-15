<?php

namespace Matcha\Api\Controllers;

use Flight;
use Matcha\Api\Model\User;
use Matcha\Api\Services\FameCalculator;

class PassController
{
    public function store(string $username)
    {
        $target = User::find(['username' => $username]);
        $me = Flight::user();

        if (is_null($target)) {
            Flight::json([
                'success' => false,
                'message' => 'User not found'
            ], 404);
            return;
        }

        $me->pass($target);
        try {
            (new FameCalculator($me, $target))->onPass();
        } catch (\Throwable $e) {
            // calcul error
        }

        Flight::json(['success' => true,], 204);
    }
}
