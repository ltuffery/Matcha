<?php

namespace Matcha\Api\Controllers;

use Flight;
use Matcha\Api\Enums\NotificationType;
use Matcha\Api\Model\User;
use Matcha\Api\Services\FameCalculator;

class LikeController
{
    public function store(string $username)
    {
        /** @var User $me */
        $me = Flight::user();
        $target = User::find(['username' => $username]);

        if (is_null($target) || $target->isBlocking($me)) {
            Flight::json(['message' => 'User not found'], 404);
            return;
        }

        $me->like($target);
        try {
            (new FameCalculator($me, $target))->onLike();
        } catch (\Throwable $e) {
            // calcul error
        }

        if ($target->hasMatch($me->username)) {
            $notif = [
                'type' => NotificationType::MATCH,
                'message' => 'Vous avez match avec ' . $me->first_name . " ! Ecrivez lui un message !",
            ];
        } else {
            $notif = [
                'type' => NotificationType::LIKE,
                'message' => ucfirst($me->first_name) . ' vous a like !',
            ];
        }

        Flight::notifier()->notify($target, $notif, private: false);
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

        /** @var User $me */
        $me = Flight::user();

        if ($user->hasMatch($me->username)) {
            Flight::notifier()->notify($user, [
                'type' => NotificationType::MESSAGE,
                'message' => "Finalement " . ucfirst($me->first_name) . " vous a unlike...",
            ], private: false);
        }

        $me->unlike($user);
        try {
            (new FameCalculator($me, $user))->onUnLike();
        } catch (\Throwable $e) {
            // calcul error
        }

        Flight::json([], 203);
    }
}
