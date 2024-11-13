<?php

namespace Matcha\Api\controllers;

use Exception;
use Flight;
use Matcha\Api\entity\User;

class AuthController
{
    /**
     * @throws Exception
     */
    public function register(): void
    {
        $request = Flight::request();

        $user = new User();
        $user->username = $request->data->username;
        $user->email = $request->data->email;
        $user->password = password_hash($request->data->password, PASSWORD_DEFAULT);

        $saved = $user->save();

        if ($saved) {
            Flight::json(['success' => true], 201);
        } else {
            Flight::json(['success' => false], 500);
        }
    }
}