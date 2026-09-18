<?php

namespace Controllers\Auth;

use Matcha\Api\Model\User;
use Tests\MatchaTestCase;

class VerifyTokenControllerTest extends MatchaTestCase
{
    public function testVerifyTokenSuccess(): void
    {
        /** @var User $user */
        $user = User::factory()->create();

        $response = $this->post('/auth/verify-token', [
            'token' => $user->generateJWT(),
        ]);

        $response->assertStatus(200);
        $response->assertJson([
            'success' => true,
        ]);
    }

    public function testVerifyTokenFail(): void
    {
        $response = $this->post('/auth/verify-token', [
            'token' => 'test.test.test',
        ]);

        $response->assertStatus(200);
        $response->assertJson([
            'success' => false,
        ]);
    }
}