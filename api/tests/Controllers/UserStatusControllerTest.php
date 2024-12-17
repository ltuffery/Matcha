<?php

use Firebase\JWT\JWT;
use flight\util\Collection;
use Matcha\Api\Controllers\UserStatusController;
use Matcha\Api\Model\User;
use Matcha\Api\Testing\Cases\DatabaseTestCase;
use Matcha\Api\Testing\TestResponse;
use PHPUnit\Framework\TestCase;

class UserStatusControllerTest extends TestCase
{
    use DatabaseTestCase;

    private UserStatusController $controller;
    private TestResponse $response;

    public function setUp(): void
    {
        $this->controller = new UserStatusController();
        $this->response = new TestResponse();

        $this->setUpDatabase();
        $jwt = $this->createUser();

        $_SERVER['HTTP_AUTHORIZATION'] = "Bearer " . $jwt;

        ob_start();
    }

    public function tearDown(): void
    {
        ob_get_clean();
        Flight::response()->clear();
    }

    private function createUser(): string
    {
        $user = new User();

        $user->username = "test";
        $user->email = "test@test.com";
        $user->password = "pass";

        $user->create();

        return JWT::encode(['username' => 'test'], getenv('SECRET_KEY'), 'HS256');
    }

    public function testWithMissingStateData(): void
    {
        Flight::request()->data = new Collection();

        try {
            $this->controller->update();

            $this->fail();
        } catch (Exception) {
            $this->response->assertStatus(400);
            $this->response->assertJson([
                'code' => 0,
                'message' => 'state is required',
            ]);
        }
    }

    public function testStateFalseValue(): void
    {
        Flight::request()->data = new Collection(['state' => false]);

        $this->controller->update();

        $user = User::find(['username' => "test"]);

        $this->assertFalse($user->online);
        $this->response->assertStatus(204);
    }

    public function testStateTrueValue(): void
    {
        Flight::request()->data = new Collection(['state' => true]);

        $this->controller->update();

        $user = User::find(['username' => "test"]);

        $this->assertTrue($user->online);
        $this->response->assertStatus(204);
    }

    public function testStateChangesTrueFalseTrue(): void
    {
        $state = true;

        for ($i = 0; $i < 3; $i++) {
            Flight::request()->data = new Collection(['state' => $state]);

            $this->controller->update();

            $user = User::find(['username' => "test"]);

            $this->assertEquals($state, $user->online);
            $this->response->assertStatus(204);

            $state = !$state;
        }
    }
}