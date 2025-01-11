<?php

namespace Matcha\Api\Exceptions;

class UniqueConstraindException extends \Exception
{
    public function __construct(string $message)
    {
        parent::__construct($message);
    }
}