<?php

use Matcha\Api\Model\User;
use Matcha\Api\Testing\Cases\DatabaseTestCase;
use Matcha\Api\Testing\Cases\HttpTestCase;
use PHPUnit\Framework\TestCase;

class RefreshTokenControllerTest extends TestCase
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
    }

    public function testWithoutRefreshToken(): void
    {
        $response = $this->post('/auth/refresh');

        var_dump(Flight::router()->getRoutes());

        $response->assertStatus(400);
    }

    public function testWithInvalidRefreshToken(): void
    {
        $response = $this->post('/auth/refresh', [
            'refresh' => $this->user->generateJWT(),
        ]);

        $response->assertStatus(401);
    }

    public function testWithValidRefreshToken(): void
    {
        $response = $this->post('/auth/refresh', [
            'refresh' => $this->user->generateRefreshJWT('127.0.0.1'),
        ]);

        $response->assertStatus(200);
        $response->assertJsonKeys(['token']);
    }
}