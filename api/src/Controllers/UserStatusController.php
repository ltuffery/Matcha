<?php

namespace Matcha\Api\Controllers;

use Flight;
use Matcha\Api\Validator\Validator;

class UserStatusController
{
    public function update()
    {
        Validator::make([
            'state' => 'required',
        ]);

        /** @var \Matcha\Api\Model\User */
        $user = Flight::user()->model();

        $user->online = Flight::request()->data->state;
        $user->save();

        Flight::jsonHalt([], 204);
    }
}
