<?php

namespace Matcha\Api\Exceptions;

use Exception;

class AutoLikeException extends Exception
{

    public function __construct()
    {
        parent::__construct("A user cannot like themselves.");
    }

}