<?php

use Matcha\Api\Model\Message;
use Matcha\Api\Model\User;
use Matcha\Api\Testing\Cases\DatabaseTestCase;
use Matcha\Api\Testing\Cases\HttpTestCase;
use PHPUnit\Framework\TestCase;

class ChatControllerTest extends TestCase
{
    use HttpTestCase;
    use DatabaseTestCase;

    private User $me;
    private User $other;

    public function setUp(): void
    {
        $this->setUpDatabase();

        $users = User::factory()->count(2)->create();

        $this->me = $users[0];
        $this->other = $users[1];

        $this->me->like($this->other);
        $this->other->like($this->me);
    }

    public function tearDown(): void
    {
        Flight::response()->clear();
    }

    /**
     * @throws Exception
     */
    private function createMessage(User $from, User $to): Message
    {
        $message = new Message();

        $message->sender_id = $from->id;
        $message->receiver_id = $to->id;
        $message->content = faker()->sentence();

        return $message->save();
    }

    public function testCreateMessage(): void
    {
        $content = faker()->sentence();

        $response = $this->withHeader([
            'Authorization' => 'Bearer ' . $this->me->generateJWT(),
        ])->post('/users/me/matches/' . $this->other->username, [
            'content' => $content,
        ]);

        $response->assertStatus(201);
    }

    public function testGetMatches(): void
    {
        $response = $this->withHeader([
            'Authorization' => 'Bearer ' . $this->me->generateJWT(),
        ])->get('/users/me/matches');

        $response->assertStatus(200);
        $response->assertCount(1);
    }

    public function testGetAllConversation(): void
    {
        for ($i = 0; $i < 5; $i++) {
            $this->createMessage($this->me, $this->other);
        }

        $response = $this->withHeader([
            'Authorization' => 'Bearer ' . $this->me->generateJWT(),
        ])->get('/users/me/matches/' . $this->other->username);

        $response->assertStatus(200);
        $response->assertCount(5);
    }

    public function testViewAllMessage(): void
    {
        for ($i = 0; $i < 5; $i++) {
            $this->createMessage($this->me, $this->other);
        }

        $response = $this->withHeader([
            'Authorization' => 'Bearer ' . $this->me->generateJWT(),
        ])->get('/users/me/matches/' . $this->other->username);

        foreach ($response->getData() as $data) {
            $this->assertFalse($data['view']);
        }

        $response = $this->withHeader([
            'Authorization' => 'Bearer ' . $this->other->generateJWT(),
        ])->get('/users/me/matches/' . $this->me->username);

        foreach ($response->getData() as $data) {
            $this->assertTrue($data['view']);
        }
    }

    public function testDeleteNotFoundMessage(): void
    {
        $response = $this->withHeader([
            'Authorization' => 'Bearer ' . $this->me->generateJWT(),
        ])->delete('/users/me/matches/' . $this->other->username . '/1');

        $response->assertStatus(404);
    }

    public function testDeleteMessageSuccess(): void
    {
        $message = $this->createMessage($this->me, $this->other);

        $response = $this->withHeader([
            'Authorization' => 'Bearer ' . $this->me->generateJWT(),
        ])->delete('/users/me/matches/' . $this->other->username . '/' . $message->id);

        $response->assertStatus(203);
    }

    public function testDeleteMessageOfOtherUser(): void
    {
        $message = $this->createMessage($this->other, $this->me);

        $response = $this->withHeader([
            'Authorization' => 'Bearer ' . $this->me->generateJWT(),
        ])->delete('/users/me/matches/' . $this->other->username . '/' . $message->id);

        $response->assertStatus(403);
    }
}