<?php

namespace Matcha\Api\Validator;

use Attribute;
use Flight;

#[Attribute(Attribute::TARGET_PROPERTY)]
class EmailValidator extends Validator
{
    // private string $field;

    // public function validate(string $field): bool
    // {
    //     $this->field = $field;

    //     if (isset(Flight::request()->data->{$this->field})) {
    //         return preg_match(
    //             "/^[\w\-\.]+@([\w\-]+\.)+[\w\-]+$/",
    //             Flight::request()->data->{$this->field}
    //         );
    //     }

    //     return true;
    // }

    // public function getMessage(): string
    // {
    //     return sprintf('%s is not a valid email address', $this->field);
    // }

    public function test($value): bool
    {
        return is_string(
            filter_var($value, FILTER_VALIDATE_EMAIL)
        );
    }
}
