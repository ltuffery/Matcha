<?php

use Matcha\Api\Model\User;
use Matcha\Api\Testing\Cases\DatabaseTestCase;
use PHPUnit\Framework\TestCase;

class UserTest extends TestCase
{
    use DatabaseTestCase;

    public function setUp(): void
    {
        $this->setUpDatabase();
    }

    public function testMatches()
    {
        /** @var User[] $users */
        $users = User::factory()->count(2)->create();

        $users[0]->like($users[1]);
        $users[1]->like($users[0]);

        $this->assertCount(1, $users[0]->matches());
        $this->assertCount(1, $users[1]->matches());
    }
}