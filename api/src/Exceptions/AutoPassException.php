<?php

namespace Matcha\Api\Exceptions;

use Exception;

class AutoPassException extends Exception
{

    public function __construct()
    {
        parent::__construct("A user cannot pass themselves.");
    }

}
