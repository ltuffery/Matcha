<?php

namespace Matcha\Api\Validator;

use Flight;

class RequiredValidator extends Validator
{

    private string $field;

    public function validate(string $field): bool
    {
        $this->field = $field;
        $request = Flight::request();

        return isset($request->data->{$field});
    }

    public function getCode(): int
    {
        return 1;
    }

    public function getMessage(): string
    {
        return sprintf('%s is required', $this->field);
    }

}