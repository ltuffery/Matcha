<?php

namespace Matcha\Api\Controllers;

use Exception;
use Firebase\JWT\JWT;
use Firebase\JWT\Key;
use Flight;
use Matcha\Api\Model\User;
use Matcha\Api\Validator\Validator;
use stdClass;

class RefreshTokenController
{
    public function store(): void
    {
        Validator::required([
            'refresh',
        ]);

        try {
            $token = JWT::decode(Flight::request()->data->refresh, new Key(getenv('SECRET_KEY'), 'HS256'));

            if (!$this->validIp($token)) {
                Flight::json([
                    'message' => 'Unauthorized',
                ], 401);

                return;
            }

            $user = User::morph([
                'username' => $token->username,
            ]);

            Flight::json([
                'token' => $user->generateJWT(),
            ]);
        } catch (Exception) {
            Flight::json([
                'message' => 'Unauthorized',
            ], 401);
        }
    }

    private function validIp(stdClass $token): bool
    {
        $ip = Flight::request()->ip;

        return $ip == $token->ip;
    }
}
