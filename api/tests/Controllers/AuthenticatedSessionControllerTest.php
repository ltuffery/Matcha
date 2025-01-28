<?php

namespace Tests\Controllers;

use Exception;
use Flight;
use Matcha\Api\Model\User;
use Matcha\Api\Testing\Cases\DatabaseTestCase;
use Matcha\Api\Testing\Cases\HttpTestCase;
use PHPUnit\Framework\TestCase;

class AuthenticatedSessionControllerTest extends TestCase
{

    use HttpTestCase;
    use DatabaseTestCase;

    private User $user;

    public function setUp(): void
    {

        $this->setUpDatabase();

        $this->user = User::factory()->create()[0];
    }

    public function tearDown(): void
    {
        Flight::response()->clear();

        restore_error_handler();
        restore_exception_handler();
    }

    public function testLoginWithNoData()
    {
        $this->expectException(Exception::class);

        $response = $this->post('/auth/login');

        $response->assertStatus(400);
    }

    public function testLoginWithUnregister()
    {
        $response = $this->post('/auth/login', [
            'username' => 'notExist',
            'password' => 'password',
        ]);

        $response->assertStatus(400);
        $response->assertJson([
            'success' => false,
        ]);
    }

    public function testLoginWithWrongPassword()
    {
        $response = $this->post('/auth/login', [
            'username' => $this->user->username,
            'password' => 'wrong',
        ]);

        $response->assertStatus(400);
        $response->assertJson([
            'success' => false,
        ]);
    }

    public function testLoginWithValidData()
    {;

        $response = $this->post('/auth/login', [
            'username' => $this->user->username,
            'password' => 'password',
        ]);

        $response->assertStatus(200);
    }
}