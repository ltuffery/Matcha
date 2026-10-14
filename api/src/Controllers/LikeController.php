<?php

namespace Matcha\Api\Controllers;

use Flight;
use Matcha\Api\Enums\NotificationType;
use Matcha\Api\Model\User;

class LikeController
{
    public function store(string $username)
    {
        /** @var User $me */
        $me = Flight::user();
        $user = User::find([
            'username' => $username,
        ]);

        if (is_null($user)) {
            Flight::json([
                'message' => 'User not found',
            ], 404);

            return;
        }

        $me->like($user);

        if ($user->hasMatch($me->username)) {
            $notif = [
                'type' => NotificationType::MATCH,
                'message' => 'Vous avez match avec ' . $me->first_name . " ! Ecrivez lui un message !",
            ];
        } else {
            $notif = [
                'type' => NotificationType::MATCH,
                'message' => ucfirst($me->first_name) . ' vous a like !',
            ];
        }

        Flight::notifier()->notify($user, $notif, private: false);

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

        /** @var User $me */
        $me = Flight::user();

        $me->unlike($user);

        if ($user->hasMatch($me->username)) {
            Flight::notifier()->notify($user, [
                'type' => NotificationType::MESSAGE,
                'message' => "Finalement " . ucfirst($me->first_name) . " vous a unlike...",
            ], private: false);
        }

        Flight::json([], 203);
    }
}
