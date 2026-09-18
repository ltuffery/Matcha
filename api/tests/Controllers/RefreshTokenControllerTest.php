<?php

namespace Controllers;

use Matcha\Api\Model\User;
use Tests\MatchaTestCase;

class RefreshTokenControllerTest extends MatchaTestCase
{
    private User $user;

    public function setUp(): void
    {
        parent::setUp();

        $this->user = User::factory()->create();
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