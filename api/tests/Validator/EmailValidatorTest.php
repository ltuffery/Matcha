<?php

namespace Matcha\Api\Validator;

use PHPUnit\Framework\TestCase;

class EmailValidatorTest extends TestCase
{
    private EmailValidator $validator;

    protected function setUp(): void
    {
        $this->validator = new EmailValidator();
    }

    public function testValidate()
    {
        $this->assertTrue($this->validator->validate('foo@bar.com'));
    }

    public function testValidateInvalid()
    {
        $this->assertFalse($this->validator->validate('foo.bar.com'));
        $this->assertFalse($this->validator->validate('@bar.com'));
        $this->assertFalse($this->validator->validate('foo@.com'));
    }

    public function testGetCode()
    {
        $this->assertEquals(2, $this->validator->getCode());
    }
}
