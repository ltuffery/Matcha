<?php

namespace Matcha\Api\Validator\Asserts;

use Attribute;

#[Attribute(Attribute::TARGET_PROPERTY)]
class NotBlank implements Assert
{
    public function assert(mixed $value): bool
    {
        return !empty($value);
    }
}
