<?php

namespace Matcha\Api\Exceptions;

class UniqueConstraintException extends \Exception
{
    public function __construct(string $message)
    {
        parent::__construct($message);
    }
}
