<?php

use Matcha\Api\Model\User;
use Matcha\Api\Testing\Cases\DatabaseTestCase;
use Matcha\Api\Testing\Cases\HttpTestCase;
use PHPUnit\Framework\TestCase;

class LocalisationControllerTest extends TestCase
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

    public function testWithNoData(): void
    {
        $response = $this->withHeader(
                ['Authorization' => 'Bearer ' . $this->user->generateJWT()]
            )
            ->put('/users/me/localisation');

        $response->assertStatus(202);
    }

    public function testWithValidData(): void
    {
        $response = $this->withHeader(
                ['Authorization' => 'Bearer ' . $this->user->generateJWT()]
            )
            ->put('/users/me/localisation', [
                'lat' => 1.555,
                'lon' => 2.555,
            ]);

        $response->assertStatus(204);
    }
}