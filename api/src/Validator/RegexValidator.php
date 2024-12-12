<?php

namespace Matcha\Api\Validator;

use Flight;

class RegexValidator extends Validator
{
    private string $field;

    public function validate(string $field): bool
    {
        $this->field = $field;

        if (!isset(self::$options[0])) {
            return false;
        }

        if (!isset(Flight::request()->data->{$field})) {
            return true;
        }

        $matches = [];

        preg_match('/' . self::$options[0] . '/m', Flight::request()->data->{$field}, $matches);

        return count($matches) > 0;
    }

    public function getMessage(): string
    {
        return sprintf("%s does not respect the %s regex", $this->field, self::$options[0]);
    }

}
