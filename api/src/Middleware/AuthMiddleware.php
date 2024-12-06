<?php

namespace Matcha\Api\Middleware;

use Firebase\JWT\JWT;
use Firebase\JWT\Key;
use Flight;

class AuthMiddleware
{

    public function before(): bool
    {
        $jwt = Flight::request()->header('Authorization', null);

        if (is_null($jwt)) {
            return false;
        }

        JWT::decode($jwt, new Key($_ENV['SECRET_KEY'], 'HS256'));

        return true;
    }

}