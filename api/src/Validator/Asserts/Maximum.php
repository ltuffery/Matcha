<?php

namespace Matcha\Api\Validator\Asserts;

use Attribute;

#[Attribute(Attribute::TARGET_PROPERTY)]
class Maximum implements Assert
{
    private int $n;
    private bool $acceptEquals;

    public function __construct(int $n, bool $acceptEquals = false)
    {
        $this->n = $n;
        $this->acceptEquals = $acceptEquals;
    }

    public function assert(mixed $value): bool
    {
        if ($this->acceptEquals) {
            return (is_numeric($value) && $value <= $this->n)
                || (is_string($value) && strlen($value) <= $this->n)
                || (is_array($value) && count($value) <= $this->n);
        }

        return (is_numeric($value) && $value < $this->n)
                || (is_string($value) && strlen($value) < $this->n)
                || (is_array($value) && count($value) < $this->n);
    }
}
