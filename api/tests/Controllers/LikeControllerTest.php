<?php

use Matcha\Api\Controllers\LikeController;
use Matcha\Api\Model\User;
use PHPUnit\Framework\TestCase;
use Matcha\Api\Testing\Cases\DatabaseTestCase;
use Matcha\Api\Testing\TestResponse;

class LikeControllerTest extends TestCase
{
    use DatabaseTestCase;

    private LikeController $controller;
    private TestResponse $response;
    private User $user;

    public function setUp(): void
    {
        $this->controller = new LikeController();
        $this->response = new TestResponse();

        $this->setUpDatabase();

        $this->user = User::factory()->create()[0];
        $_SERVER['HTTP_AUTHORIZATION'] = "Bearer " . $this->user->generateJWT();
    }

    public function tearDown(): void
    {
        Flight::response()->clear();
    }

    public function testLikeUserNotFound(): void
    {
        $this->controller->store('notfound');

        $this->response->assertStatus(404);
        $this->response->assertJson([
            'message' => 'User not found',
        ]);
    }
}