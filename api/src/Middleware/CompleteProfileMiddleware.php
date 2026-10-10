<?php

namespace Matcha\Api\Middleware;

use Flight;
use Matcha\Api\Enums\ErrorsCode;
use Matcha\Api\Model\User;

class CompleteProfileMiddleware
{

    public function before($params): void
    {
        /** @var User $user */
        $user = Flight::user();

        if (is_null($user)) {
            Flight::jsonHalt(['message' => 'Unauthenticated', 'code' => ErrorsCode::UNAUTHENTICATED], 403);
        }

        if (!$user->hasCompleteProfile()) {
            Flight::jsonHalt(['message' => 'Forbidden', 'code' => ErrorsCode::PROFILE_INCOMPLETE], 403);
        }
    }
}