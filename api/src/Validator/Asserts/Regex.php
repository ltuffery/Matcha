<?php

namespace Matcha\Api\Validator\Asserts;

use Attribute;

#[Attribute(Attribute::TARGET_PROPERTY)]
class Regex extends Assert
{
    private string $regex;

    public function __construct(string $regex)
    {
        $this->regex = $regex;
    }

    public function test(mixed $value): bool
    {
        $matches = [];

        preg_match('/' . $this->regex . '/m', $value, $matches);

        return count($matches) > 0;
    }

}
