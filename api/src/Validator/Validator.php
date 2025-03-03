<?php

namespace Matcha\Api\Validator;

use Flight;
use Matcha\Api\Exceptions\InvalidDataException;

abstract class Validator
{
    public static function required(array $data): void
    {
        $collection = Flight::request()->data;

        foreach ($data as $value) {
            if (!in_array($value, $collection->data)) {
                throw new InvalidDataException(0, $data . " is required.");
            }
        }
    }
}
