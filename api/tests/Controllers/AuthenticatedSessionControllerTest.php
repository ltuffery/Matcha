<?php

namespace Controllers;

use Exception;
use Flight;
use flight\util\Collection;
use Matcha\Api\Controllers\AuthenticatedSessionController;
use Matcha\Api\Model\User;
use Matcha\Api\Testing\TestResponse;
use Tests\MatchaTestCase;

class AuthenticatedSessionControllerTest extends MatchaTestCase
{

    private TestResponse $response;
    private AuthenticatedSessionController $controller;
    private User $user;

    public function setUp(): void
    {
        parent::setUp();

        $this->response = new TestResponse();
        $this->controller = new AuthenticatedSessionController();

        $this->user = User::factory()->create();
    }

    public function testLoginWithNoData()
    {
        Flight::request()->data = new Collection();

        try {
            $this->controller->store();

            $this->fail();
        } catch (Exception) {
            $this->response->assertStatus(400);
            $this->response->assertJson([
                'code' => 0,
                'message' => "username is required",
            ]);
        }
    }

    public function testLoginWithUnexistUser()
    {
        Flight::request()->data = new Collection([
            'username' => 'unexist',
            'password' => 'password',
        ]);

        $this->controller->store();

        $this->response->assertStatus(400);
        $this->response->assertJson([
            'success' => false,
        ]);
    }

    public function testLoginWithWrongPassword()
    {
        Flight::request()->data = new Collection([
            'username' => $this->user->username,
            'password' => 'wrong',
        ]);

        $this->controller->store();

        $this->response->assertStatus(400);
        $this->response->assertJson([
            'success' => false,
        ]);
    }

    public function testLoginWithValidData()
    {
        Flight::request()->data = new Collection([
            'username' => $this->user->username,
            'password' => 'password',
        ]);

        $this->controller->store();

        $this->response->assertStatus(200);
    }
}