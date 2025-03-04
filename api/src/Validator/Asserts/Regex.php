<?php

namespace Matcha\Api\Validator\Asserts;

use Attribute;

#[Attribute(Attribute::TARGET_PROPERTY)]
class Regex implements Assert
{
    private string $regex;

    public function __construct(string $regex)
    {
        $this->regex = $regex;
    }

    public function assert(mixed $value): bool
    {
        $matches = [];

        preg_match('/' . $this->regex . '/m', $value, $matches);

        return count($matches) > 0;
    }

}
