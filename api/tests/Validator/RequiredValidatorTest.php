<?php

use Matcha\Api\Validator\RequiredValidator;
use PHPUnit\Framework\TestCase;

class RequiredValidatorTest extends TestCase
{
    private RequiredValidator $validator;

    protected function setUp(): void
    {
        $this->validator = new RequiredValidator();
    }

    public function testValidate()
    {
        Flight::request()->data['username'] = 'ltuffery';

        $this->assertTrue($this->validator->validate('username'));
    }

    public function testValidateInvalid()
    {
        $this->assertFalse($this->validator->validate('unexist'));
    }
}