<?php

namespace Matcha\Api\Validator;

use Flight;
use InvalidDataException;

abstract class Validator
{
    private static array $rules = [
        'required' => RequiredValidator::class,
        'email' => EmailValidator::class,
        'number' => NumberValidator::class,
    ];

    public static function make(array $rules): void
    {
        foreach ($rules as $name => $value) {
            $split = explode("|", $value);

            foreach ($split as $rule) {
                if (isset(self::$rules[$rule])) {
                    /** @var Validator $class */
                    $class = new self::$rules[$rule]();

                    if (!$class->validate($name)) {
                        Flight::response()->clear();
                        Flight::json([
                            'code' => $class->getCode(),
                            'message' => $class->getMessage(),
                        ], 400);

                        throw new InvalidDataException($class->getCode(), $class->getMessage());
                    }
                }
            }
        }
    }

    abstract public function validate(string $field): bool;

    abstract public function getCode(): int;

    abstract public function getMessage(): string;

}
