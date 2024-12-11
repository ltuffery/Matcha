<?php

namespace Controllers;

use Exception;
use Flight;
use flight\util\Collection;
use Matcha\Api\Controllers\AuthenticatedSessionController;
use Matcha\Api\Model\User;
use Matcha\Api\Testing\Cases\DatabaseTestCase;
use Matcha\Api\Testing\TestResponse;
use PHPUnit\Framework\TestCase;

class AuthenticatedSessionControllerTest extends TestCase
{

    use DatabaseTestCase {
        DatabaseTestCase::setUp as setUpDatabase;
    }
    
    private TestResponse $response;
    private AuthenticatedSessionController $controller;

    protected function setUp(): void
    {
        $this->response = new TestResponse();
        $this->controller = new AuthenticatedSessionController();

        Flight::response()->clearBody();
        $this->setUpDatabase();
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

        $this->response->assertStatus(404);
        $this->response->assertJson([
            'success' => false,
        ]);
    }

    public function testLoginWithWrongPassword()
    {
        Flight::request()->data = new Collection([
            'username' => 'test',
            'password' => 'wrong',
        ]);

        $this->controller->store();

        $this->response->assertStatus(400);
        $this->response->assertJson([
            'success' => false,
            'message' => 'wrong password',
        ]);
    }

}