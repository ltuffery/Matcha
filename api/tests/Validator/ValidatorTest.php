<?php

use Matcha\Api\Validator\Validator;
use PHPUnit\Framework\TestCase;

class ValidatorTest extends TestCase
{

    public function testEmptyMake()
    {
        $this->expectNotToPerformAssertions();

        Validator::required([]);
    }

}