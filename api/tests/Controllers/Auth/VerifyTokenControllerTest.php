<?php

namespace Controllers\Auth;

use Flight;
use Matcha\Api\Model\User;
use Matcha\Api\Testing\Cases\DatabaseTestCase;
use Matcha\Api\Testing\Cases\HttpTestCase;
use PHPUnit\Framework\TestCase;

class VerifyTokenControllerTest extends TestCase
{
    use HttpTestCase;
    use DatabaseTestCase;

    protected function setUp(): void
    {
        $this->setUpDatabase();
    }

    protected function tearDown(): void
    {
        Flight::response()->clear();
    }

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