<?php

namespace Matcha\Api\Validator;

use Flight;
use PHPUnit\Framework\TestCase;

class EmailValidatorTest extends TestCase
{
    private EmailValidator $validator;

    protected function setUp(): void
    {
        $this->validator = new EmailValidator();
    }

    private function check(string $test): bool
    {
        Flight::request()->data['email'] = $test;

        return $this->validator->validate('email');
    }

    public function testValidate()
    {
        $this->assertTrue($this->check('test@test.com'));
    }

    public function testValidateInvalid()
    {
        $this->assertFalse($this->check('foo.bar.com'));
        $this->assertFalse($this->check('@bar.com'));
        $this->assertFalse($this->check('foo@.com'));
    }
}
