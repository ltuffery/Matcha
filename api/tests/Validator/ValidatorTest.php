<?php

namespace Tests\Validator;

use Matcha\Api\Validator\Validator;
use Tests\MatchaTestCase;

class ValidatorTest extends MatchaTestCase
{

    public function testEmptyMake()
    {
        $this->expectNotToPerformAssertions();

        Validator::required([]);
    }

}