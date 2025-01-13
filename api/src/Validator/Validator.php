<?php

namespace Matcha\Api\Validator;

use Flight;
use Matcha\Api\Exceptions\InvalidDataException;

abstract class Validator
{
    protected static array $options;
    private static array $rules = [
        'required' => RequiredValidator::class,
        'email' => EmailValidator::class,
        'number' => NumberValidator::class,
        'regex' => RegexValidator::class,
    ];

    public static function make(array $rules): void
    {
        foreach ($rules as $name => $value) {
            $split = explode("|", $value);

            foreach ($split as $rule) {
                self::findRule($name, $rule);
            }
        }
    }

    private static function findRule(string $name, string $rule): void
    {
        $params = explode(":", $rule);
        $rule = $params[0];
        self::$options = array_slice($params, 1);

        if (isset(self::$rules[$rule])) {
            /** @var Validator $class */
            $class = new self::$rules[$rule]();
            $code = array_search($rule, array_keys(self::$rules));

            if (!$class->validate($name)) {
                Flight::response()->clearBody();
                Flight::json([
                    'code' => $code,
                    'message' => $class->getMessage(),
                ], 400);

                throw new InvalidDataException($code, $class->getMessage());
            }
        }
    }

    abstract public function validate(string $field): bool;

    abstract public function getMessage(): string;

}
