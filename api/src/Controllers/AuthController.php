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
        ]);

        if ($user == null) {
            Flight::json([
                'success' => false,
            ]);
        } else if (password_verify($request->data->password, $user->password)) {
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