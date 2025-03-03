<?php

namespace Matcha\Api\Validator\Asserts;

use Attribute;

#[Attribute(Attribute::TARGET_PROPERTY)]
class Email extends Assert
{
    public function test(mixed $value): bool
    {
        return is_string(
            filter_var($value, FILTER_VALIDATE_EMAIL)
        );
    }
}
