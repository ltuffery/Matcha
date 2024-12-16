<?php

use flight\util\Collection;
use Matcha\Api\Controllers\RegisterController;
use Matcha\Api\Testing\Cases\DatabaseTestCase;
use Matcha\Api\Testing\TestResponse;
use PHPUnit\Framework\TestCase;

class RegisterControllerTest extends TestCase
{
    use DatabaseTestCase;

    private RegisterController $controller;
    private TestResponse $response;

    public function setUp(): void
    {
        $this->controller = new RegisterController();
        $this->response = new TestResponse();

        $this->setUpDatabase();
    }

    public function tearDown(): void
    {
        Flight::response()->clear();
    }

    public function testWithEmptyRequest(): void
    {
        Flight::request()->data = new Collection();

        try {
            $this->controller->store();

            $this->fail();
        } catch (\Exception) {
            $this->response->assertStatus(400);
        }
    }

    public function testWithBadUsername(): void
    {
        Flight::request()->data = new Collection([
            'username' => "test",
            'email' => "test@test.com",
            'password' => "password",
            'age' => 19,
            'first_name' => 'John',
            'last_name' => 'Doe',
            'gender' => 'M',
            'sexual_preferences' => 'F',
            'biography' => 'Lorem lorem',
        ]);

        try {
            $this->controller->store();

            $this->fail();
        } catch (Exception) {
            $this->response->assertStatus(400);
        }
    }

    public function testWithValidData(): void
    {
        Flight::request()->data = new Collection([
            'username' => "john.doe",
            'email' => "test@test.com",
            'password' => "password",
            'age' => 19,
            'first_name' => 'John',
            'last_name' => 'Doe',
            'gender' => 'M',
            'sexual_preferences' => 'F',
            'biography' => 'Lorem lorem',
        ]);

        try {
            $this->controller->store();

            $this->fail();
        } catch (Exception) {
            $this->response->assertStatus(201);
        }
    }
}