<?php

namespace Matcha\Api\Controllers;

use Flight;
use Matcha\Api\Model\User;
use Matcha\Api\Validator\Validator;

class UserStatusController
{
    public function update(): void
    {
        Validator::make([
            'state' => 'required',
        ]);

        /** @var User $user */
        $user = Flight::user();

        $user->online = Flight::request()->data->state;
        $user->save();

        Flight::jsonHalt([], 204);
    }
}
