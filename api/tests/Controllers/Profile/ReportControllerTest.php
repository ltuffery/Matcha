<?php

namespace Controllers\Profile;

use Flight;
use Matcha\Api\Model\User;
use Matcha\Api\Testing\Cases\DatabaseTestCase;
use Matcha\Api\Testing\Cases\HttpTestCase;
use PHPUnit\Framework\TestCase;

class ReportControllerTest extends TestCase
{
    use HttpTestCase;
    use DatabaseTestCase;

    private User $user;

    protected function setUp(): void
    {
        $this->setUpDatabase();

        $this->user = User::factory()->create();
    }

    protected function tearDown(): void
    {
        Flight::response()->clear();
    }

    public function testReportOtherUser(): void
    {
        /** @var User $user2 */
        $user2 = User::factory()->create();

        $response = $this->withHeader([
            'Authorization' => 'Bearer ' . $this->user->generateJWT(),
        ])->post('/users/' . $user2->username . '/report', [
            'raison' => 'Fake Account'
        ]);

        $response->assertStatus(201);
    }

    public function testReportUserNotFound(): void
    {
        $response = $this->withHeader([
            'Authorization' => 'Bearer ' . $this->user->generateJWT(),
        ])->post('/users/unknown/report', [
            'raison' => 'Fake Account'
        ]);

        $response->assertStatus(403);
    }

    public function testReportMe(): void
    {
        $response = $this->withHeader([
            'Authorization' => 'Bearer ' . $this->user->generateJWT(),
        ])->post('/users/' . $this->user->username . '/report', [
            'raison' => 'Fake Account'
        ]);

        $response->assertStatus(403);
    }
}