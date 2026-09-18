<?php

namespace Controllers\Profile;

use Matcha\Api\Model\User;
use Tests\MatchaTestCase;

class ReportControllerTest extends MatchaTestCase
{
    private User $user;

    protected function setUp(): void
    {
        parent::setUp();

        $this->user = User::factory()->create();
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