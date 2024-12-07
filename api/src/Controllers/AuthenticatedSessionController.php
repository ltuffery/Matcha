<?php

namespace Matcha\Api\Controllers;

use Firebase\JWT\JWT;
use Firebase\JWT\Key;
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

        $user = User::find([
            'username' => $request->data->username,
        ]);

        if ($user == null) {
            Flight::json([
                'success' => false,
            ]);
        } elseif (password_verify($request->data->password, $user->password)) {
            if (!$user->email_verified)
            {
                Flight::json([
                    'success' => false,
                    'error' => "Your email is not verified",
                ]);
            }
            $time = time();

            $jwt = JWT::encode([
                'exp' => $time + 600,
                'iat' => $time,
                'username' => $user->username,
            ], $_ENV['SECRET_KEY'], 'HS256');

            Flight::json([
                'success' => true,
                'token' => $jwt,
            ]);
        } else {
            Flight::json([
                'success' => false,
            ]);
        }
    }
}
