<?php

namespace Matcha\Api\Controllers;

use Flight;
use InvalidDataException;
use Matcha\Api\Model\User;
use Matcha\Api\Validator\Validator;
use ReflectionException;

class AuthController
{
    /**
     * @throws InvalidDataException
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

    /**
     * @throws InvalidDataException
     * @throws ReflectionException
     */
    public function login(): void
    {
        Validator::make([
            'username' => 'required',
            'password' => 'required',
        ]);

        $request = Flight::request();

        $user = User::find([
            'username' => $request->data->username,
        ])[0];

        if ($user == null) {
            Flight::json([
                'success' => false,
            ]);
        }

        if (password_verify($request->data->password, $user->password)) {
            Flight::json([
                'success' => true,
            ]);
        } else {
            Flight::json([
                'success' => false,
            ]);
        }
    }
}