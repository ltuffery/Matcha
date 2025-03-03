<?php

namespace Matcha\Api\Validator\Asserts;

class NotBlank extends Assert
{
    public function test(mixed $value): bool
    {
        return !empty($value);
    }
}