<?php

namespace Matcha\Api\Validator\Asserts;

use Attribute;

#[Attribute(Attribute::TARGET_PROPERTY)]
class Email implements Assert
{
    public function assert(mixed $value): bool
    {
        return is_string(
            filter_var($value, FILTER_VALIDATE_EMAIL)
        );
    }

    public function getErrorMessage(): string
    {
        return "%s is not a valid email address";
    }
}
