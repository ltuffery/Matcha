<?php

namespace Matcha\Api\Exceptions;

class PhotoException extends \Exception
{
    public function __construct()
    {
        parent::__construct("Photo exception.");
    }
}