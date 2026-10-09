<?php

namespace Matcha\Api\Controllers;

use Flight;
use Matcha\Api\Exceptions\InvalidDataException;
use Matcha\Api\Model\User;
use Matcha\Api\Validator\Validator;

class AuthenticatedSessionController
{
    /**
     * @throws InvalidDataException
     */
    public function store(): void
    {
        Validator::required([
            'username',
            'password',
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
                'refresh' => $user->generateRefreshJWT($request->ip),
            ]);
        } else {
            Flight::json([
                'success' => false,
                'error' => "Invalid username or password",
            ], 400);
        }
    }
}
