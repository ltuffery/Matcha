<?php

namespace Matcha\Api\Controllers;

use Flight;
use Matcha\Api\Model\User;
use Matcha\Api\Model\Pass;
use Matcha\Api\Services\FameCalculator;
use Matcha\Api\Exceptions\UniqueConstraintException;

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

        try {
            $me->pass($target);
            (new FameCalculator($me, $target))->onPass();
        } catch (UniqueConstraintException $e) {
            $pass = Pass::find(['user_id' => $me->id, 'target_id' => $target->id]);

            $pass->created_at = date('Y-m-d H:i:s');

            $pass->update(['user_id' => $me->id, 'target_id' => $target->id]);
        }
        Flight::json(['success' => true,], 204);
    }
}
