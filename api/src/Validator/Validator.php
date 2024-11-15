<?php

namespace Matcha\Api\Validator;

use Flight;
use flight\net\Request;

abstract class Validator
{
    private static array $rules = [
        'required' => RequiredValidator::class,
    ];

    public static function make(array $rules): void
    {
        foreach ($rules as $name => $value) {
            $split = preg_split('|', $value);

            foreach ($split as $rule) {
                if (isset(self::$rules[$rule])) {
                    /** @var Validator $class */
                    $class = new self::$rules[$rule];

                    if (!$class->validate($name)) {
                        Flight::jsonHalt([
                            'code' => $class->getCode(),
                            'message' => $class->getMessage(),
                        ], 400);
                        return;
                    }
                }
            }
        }
    }

    public abstract function validate(string $field): bool;

    public abstract function getCode(): int;

    public abstract function getMessage(): string;

}