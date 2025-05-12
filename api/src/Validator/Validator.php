<?php

namespace Matcha\Api\Validator;

use Flight;
use Matcha\Api\Exceptions\InvalidDataException;

abstract class Validator
{
    public static function required(array $data): void
    {
        $request = Flight::request();

        foreach ($data as $value) {
            if (!isset($request->data->{$value})) {
                Flight::json([
                    'code' => 0,
                    'message' => $value . " is required",
                ], 400);

                throw new InvalidDataException(0, $value . " is required.");
            }
        }
    }
}
