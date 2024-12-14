<?php

namespace Matcha\Api\Controllers;

use Flight;
use InvalidDataException;
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

        $authenticated = Flight::user()->authenticate($request->data->username, $request->data->password);

        if ($authenticated) {
            if (!Flight::user()->model()->email_verified) {
                Flight::json([
                    'success' => false,
                    'error' => "Your email is not verified",
                ], 401);

                return;
            }

            Flight::json([
                'success' => true,
                'token' => Flight::user()->generateJWT(),
            ]);
        } else {
            Flight::json([
                'success' => false,
            ], 400);
        }
    }
}
