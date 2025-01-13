<?php

namespace Matcha\Api\Exceptions;

class InvalidDataException extends \Exception
{
    public function __construct(int $code, string $message)
    {
        parent::__construct($message, $code);
    }

}
