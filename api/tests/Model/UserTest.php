<?php

namespace Model;

use Matcha\Api\Model\User;
use Tests\MatchaTestCase;

class UserTest extends MatchaTestCase
{
    public function testMatches()
    {
        /** @var User[] $users */
        $users = User::factory()->count(2)->create();

        $users[0]->like($users[1]);
        $users[1]->like($users[0]);

        $this->assertCount(1, $users[0]->matches());
        $this->assertCount(1, $users[1]->matches());
    }

    public function testGetAge(): void
    {
        /** @var User $users */
        $users = User::factory()->create();

        $users->birthday = "2000-01-01";
        $currentYear = (int)date('Y');

        $this->assertEquals($currentYear - 2000, $users->getAge());
    }
}