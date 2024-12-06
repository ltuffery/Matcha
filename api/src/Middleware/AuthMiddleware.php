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
        }

        try {
            JWT::decode($jwt, new Key($_ENV['SECRET_KEY'], 'HS256'));
        } catch (Exception $e) {
            Flight::jsonHalt(['message' => $e->getMessage()], 400);
        }
    }

}