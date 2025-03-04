<?php

namespace Matcha\Api\Validator\Asserts;

class NotBlank implements Assert
{
    public function assert(mixed $value): bool
    {
        return !empty($value);
    }
}