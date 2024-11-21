<?php

namespace Matcha\Api\Controllers;

use Exception;
use Flight;
use Matcha\Api\Model\User;
use Matcha\Api\Validator\Validator;

class AuthController
{
    /**
     * @throws Exception
     */
    public function register(): void
    {
        Validator::make([
            'username' => 'required',
            'email' => 'required|email',
            'password' => 'required',
        ]);

        $request = Flight::request();

        var_dump(Flight::response());

        $user = new User();
        $user->username = $request->data->username;
        $user->email = $request->data->email;
        $user->password = password_hash($request->data->password, PASSWORD_DEFAULT);

        $saved = $user->save();

        Flight::json([
            'success' => $saved,
        ], $saved ? 201 : 400);
    }
}