<?php

namespace Matcha\Api\Controllers\Auth;

use Exception;
use Firebase\JWT\JWT;
use Firebase\JWT\Key;
use Flight;
use Matcha\Api\Validator\Validator;

class VerifyTokenController
{
    public function store(): void
    {
        Validator::make([
            'token' => 'required',
        ]);

        try {
            JWT::decode(Flight::request()->data->token, new Key(getenv('SECRET_KEY'), 'HS256'));

            Flight::json([
                'success' => true,
            ]);
        } catch (Exception) {
            Flight::json([
                'success' => false,
            ]);
        }
    }
}
