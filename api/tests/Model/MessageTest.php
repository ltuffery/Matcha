<?php

use Matcha\Api\Model\Message;
use Matcha\Api\Model\User;
use Matcha\Api\Testing\Cases\DatabaseTestCase;
use PHPUnit\Framework\TestCase;

class MessageTest extends TestCase
{
    use DatabaseTestCase;

    public function setUp(): void
    {
        $this->setUpDatabase();
    }

    public function testGetLastOfMessage() {
        /** @var User[] $users */
        $users = User::factory()->count(2)->create();

        $message = new Message();

        $message->sender_id = $users[0]->id;
        $message->receiver_id = $users[1]->id;
        $message->content = "Test message";

        $message->save();

        /** @var Message $lastMessage */
        $lastMessage = Message::lastOf($users[0], $users[1]);

        $this->assertNotNull($lastMessage);
        $this->assertEquals($lastMessage->content, "Test message");
    }
}