<?php

namespace Matcha\Api\Middleware;

use Exception;
use Firebase\JWT\JWT;
use Firebase\JWT\Key;
use Flight;

class AuthMiddleware
{
    public function before($params): void
    {
        $jwt = Flight::request()->header('Authorization');

        if (empty($jwt)) {
            Flight::jsonHalt(['message' => 'Unauthorized'], 401);
            return;
        }

        $token = str_replace('Bearer ', '', $jwt);

        try {
            JWT::decode($token, new Key(getenv('SECRET_KEY'), 'HS256'));
        } catch (Exception) {
            Flight::jsonHalt(['message' => 'Unauthorized'], 401);
        }
    }

}
