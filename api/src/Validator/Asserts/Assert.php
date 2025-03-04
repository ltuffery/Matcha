<?php

namespace Matcha\Api\Validator\Asserts;

interface Assert {
    public function assert(mixed $value): bool;
}