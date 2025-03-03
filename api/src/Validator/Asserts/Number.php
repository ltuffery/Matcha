<?php

namespace Matcha\Api\Validator\Asserts;

use Attribute;

#[Attribute(Attribute::TARGET_PROPERTY)]
class Number extends Assert
{
    public function test(mixed $value): bool
    {
        return is_numeric($value);
    }
}
