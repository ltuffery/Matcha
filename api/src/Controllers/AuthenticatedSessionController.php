<?php

namespace Matcha\Api\Controllers;

use Flight;
use InvalidDataException;
use Matcha\Api\Model\User;
use Matcha\Api\Validator\Validator;
use ReflectionException;

class AuthenticatedSessionController
{
    /**
     * @throws InvalidDataException
     * @throws ReflectionException
     */
    public function store(): void
    {
        Validator::make([
            'username' => 'required',
            'password' => 'required',
        ]);

        $request = Flight::request();

        $user = User::authenticate($request->data->username, $request->data->password);

        if (is_object($user)) {
            if (!$user->email_verified) {
                Flight::json([
                    'success' => false,
                    'error' => "Your email is not verified",
                ], 401);

                return;
            }

            Flight::json([
                'success' => true,
                'token' => $user->generateJWT(),
            ]);
        } else {
            Flight::json([
                'success' => false,
            ], 400);
        }
    }
}
