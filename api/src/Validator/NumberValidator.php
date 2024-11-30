<?php

namespace Matcha\Api\Validator;

use Flight;

class NumberValidator extends Validator
{
    private string $field = '';

    public function validate(string $field): bool
    {
        $this->field = $field;
        $value = Flight::request()->data->{$field};

        return !isset($value) || is_numeric($value);
    }

    public function getMessage(): string
    {
        return sprintf('%s is not a valid number', $this->field);
    }
}
