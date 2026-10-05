<?php

namespace Matcha\Api\Validator\Asserts;

use Attribute;

#[Attribute(Attribute::TARGET_PROPERTY)]
class Number implements Assert
{
    public function assert(mixed $value): bool
    {
        return is_numeric($value);
    }

    public function getErrorMessage(): string
    {
        return "%s is not a number";
    }
}
