<?php

namespace Matcha\Api\Middleware;

use Matcha\Api\Model\User;

class LastSeenMiddleware
{
    public function before($params): void
    {
        /** @var User|null $user */
        $user = \Flight::user();

        if (is_null($user)) {
            return;
        }

        $user->last_connection = date('Y-m-d H:i:s');
        $user->save();
    }
}