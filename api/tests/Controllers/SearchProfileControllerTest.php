<?php

use Matcha\Api\Model\User;
use Matcha\Api\Testing\Cases\DatabaseTestCase;
use Matcha\Api\Testing\Cases\HttpTestCase;
use PHPUnit\Framework\TestCase;

class SearchProfileControllerTest extends TestCase
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

    public function testEmptyQuery(): void
    {
        $response = $this->withHeader([
            'Authorization' => 'Bearer ' . $this->user->generateJWT(),
        ])->get('/search/users');

        $response->assertStatus(200);
        $response->assertJson([]);
    }

    public function testQueryLengthLessThanFive(): void
    {
        $users = User::factory()->count(10)->create();
    
        for ($i = 1; $i < 5; $i++) {
            $response = $this->withHeader([
                'Authorization' => 'Bearer ' . $this->user->generateJWT(),
            ])->get('/search/users', [
                'q' => substr($users[0]->username, 0, $i),
            ]);

            $response->assertStatus(200);
            $response->assertJson([]);
            $response->assertCount(0);
        }
    }

    public function testQueryValidLength(): void
    {
        $users = User::factory()->count(10)->create();
        $subUsername = substr($users[0]->username, 0, 5);
        $finds = array_filter($users, fn (User $user) => str_starts_with($user->username, $subUsername));
    
        $response = $this->withHeader([
            'Authorization' => 'Bearer ' . $this->user->generateJWT(),
        ])->get('/search/users', [
            'q' => $subUsername,
        ]);

        $response->assertStatus(200);
        $response->assertCount(count($finds));
    }
}