<?php

namespace Matcha\Api\Controllers;

use Flight;
use Matcha\Api\Enums\NotificationType;
use Matcha\Api\Model\User;

class ViewController
{
    public function store(string $username)
    {
        /** @var User $me */
        $me = Flight::user();
        if ($me->username == $username) {
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

        $me->view($user);

        Flight::notifier()->notify($user, [
            'type' => NotificationType::VIEW,
            'message' => ucfirst($me->first_name) . " a consulter votre profile."
        ], private: false);

        Flight::json([], 204);
    }
}
