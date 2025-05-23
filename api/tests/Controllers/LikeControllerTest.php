<?php

use Matcha\Api\Controllers\LikeController;
use Matcha\Api\Exceptions\UniqueConstraintException;
use Matcha\Api\Model\Like;
use Matcha\Api\Model\User;
use PHPUnit\Framework\TestCase;
use Matcha\Api\Testing\Cases\DatabaseTestCase;
use Matcha\Api\Testing\Cases\HttpTestCase;
use Matcha\Api\Testing\TestResponse;

class LikeControllerTest extends TestCase
{
    use DatabaseTestCase;
    use HttpTestCase;

    private LikeController $controller;
    private TestResponse $response;
    private User $user;

    public function setUp(): void
    {
        $this->setUpDatabase();

        $this->user = User::factory()->create();
    }

    public function tearDown(): void
    {
        Flight::response()->clear();
    }

    public function testLikeUserNotFound(): void
    {
        $response = $this->withHeader([
            'Authorization' => 'Bearer ' . $this->user->generateJWT(),
        ])->post('/users/notfound/like');

        $response->assertStatus(404);
    }

    public function testLikeUserFound(): void
    {
        $liked = User::factory()->create();

        $response = $this->withHeader([
            'Authorization' => 'Bearer ' . $this->user->generateJWT(),
        ])->post('/users/' . $liked->username . '/like');

        $response->assertStatus(203);
    }

    public function testUnLikeUserNotFound(): void
    {
        $response = $this->withHeader([
            'Authorization' => $this->user->generateJWT(),
        ])->delete('/users/notfound/unlike');

        $response->assertStatus(404);
    }

    public function testUnLikeLikeNotFound(): void
    {
        $liked = User::factory()->create();

        $like = new Like();
        $like->user_id = $this->user->id;
        $like->liked_id = $liked->id;

        $like->save();

        $response = $this->withHeader([
            'Authorization' => 'Bearer ' . $this->user->generateJWT(),
        ])->delete('/users/' . $liked->username . '/unlike');

        $response->assertStatus(203);
    }

    public function testUnLikeUserFound(): void
    {
        $liked = User::factory()->create();

        $response = $this->withHeader([
            'Authorization' => 'Bearer ' . $this->user->generateJWT(),
        ])->delete('/users/' . $liked->username . '/unlike');

        $response->assertStatus(203);
    }

    public function testDoubleLikeUser(): void
    {
        $liked = User::factory()->create();

        $response = $this->withHeader([
            'Authorization' => 'Bearer ' . $this->user->generateJWT(),
        ])->post('/users/' . $liked->username . '/like');

        $response->assertStatus(203);

        $this->expectException(UniqueConstraintException::class);

        $response = $this->withHeader([
            'Authorization' => 'Bearer ' . $this->user->generateJWT(),
        ])->post('/users/' . $liked->username . '/like');

        $response->assertStatus(400);
    }

    public function testLikeMultipleUser(): void
    {
        $users = User::factory()->count(5)->create();

        foreach ($users as $user) {
            $response = $this->withHeader([
                'Authorization' => 'Bearer ' . $this->user->generateJWT(),
            ])->post('/users/' . $user->username . '/like');

            $response->assertStatus(203);
        }

        $this->assertCount(5, $this->user->likes());
    }
}