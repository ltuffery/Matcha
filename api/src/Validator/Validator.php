<?php

namespace Matcha\Api\Validator;

use Flight;
use InvalidDataException;

abstract class Validator
{
    private static array $rules = [
        'required' => RequiredValidator::class,
        'email' => EmailValidator::class,
    ];

    public static function make(array $rules): void
    {
        foreach ($rules as $name => $value) {
            $split = explode("|", $value);

            foreach ($split as $rule) {
                if (isset(self::$rules[$rule])) {
                    /** @var Validator $class */
                    $class = new self::$rules[$rule];

                    if (!$class->validate($name)) {
                        throw new InvalidDataException($class->getCode(), $class->getMessage());
                    }
                }
            }
        }
    }

    public abstract function validate(string $field): bool;

    public abstract function getCode(): int;

    public abstract function getMessage(): string;

}