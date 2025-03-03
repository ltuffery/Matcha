<?php

namespace Matcha\Api\Validator\Asserts;

use Attribute;

#[Attribute(Attribute::TARGET_PROPERTY)]
abstract class Assert {
    abstract public function test(mixed $value): bool;
}