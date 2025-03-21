<?php

use Matcha\Api\Model\Model;
use Matcha\Api\Model\User;
use Matcha\Api\Testing\Cases\DatabaseTestCase;
use PHPUnit\Framework\TestCase;

class ModelTest extends TestCase
{
    use DatabaseTestCase;

    private User $user;

    public function setUp(): void
    {
        $this->user = new User();

        $this->setUpDatabase();
    }

    private function createUser(): User
    {
        $this->user->username = "test";
        $this->user->email = "test@test.com";
        $this->user->password = "pass";
        $this->user->birthday = "2000-05-21";

        return $this->user->create();
    }

    private function createUsers(int $n): void
    {
        for ($i = 0; $i < $n; $i++) {
            $this->user->username = faker()->userName;
            $this->user->email = faker()->email;
            $this->user->password = "pass";
            $this->user->birthday = "2000-05-21";

            $this->user->create();
        }
    }

    public function testCreateModel(): void
    {
        $user = $this->createUser();

        $this->assertEquals(1, $user->id);
        $this->assertEquals("test", $user->username);
        $this->assertEquals("pass", $user->password);
    }

    public function testUpdateModel(): void
    {
        $user = $this->createUser();

        $user->username = "update";
        $user = $user->update();

        $this->assertEquals(1, $user->id);
        $this->assertEquals("update", $user->username);
    }

    public function testSaveWhenModelIsNew(): void
    {
        $this->user->username = "test";
        $this->user->email = "test@test.com";
        $this->user->password = "pass";
        $this->user->birthday = "2000-05-21";

        $user = $this->user->save();

        $this->assertEquals(1, $user->id);
        $this->assertEquals("test", $user->username);
        $this->assertEquals("pass", $user->password);
    }

    public function testSaveWhenModelExist(): void
    {
        $user = $this->createUser();

        $user->username = "update";
        $user = $user->save();

        $this->assertEquals(1, $user->id);
        $this->assertEquals("update", $user->username);
    }

    public function testGetTable(): void
    {
        $this->assertEquals("users", User::getTable());
    }

    public function testFillModel(): void
    {
        $this->user->fill([
            'username' => "test",
        ]);

        $this->assertEquals("test", $this->user->username);
        $this->assertFalse(isset($this->user->email));
    }

    public function testMorphModel(): void
    {
        $user = User::morph([
            'username' => 'test',
            'email' => 'test@test.com',
            'password' => 'pass',
        ]);

        $this->assertEquals(0, $user->id);
        $this->assertEquals("test", $user->username);
        $this->assertEquals("test@test.com", $user->email);
        $this->assertEquals("pass", $user->password);
        $this->assertFalse(isset($user->first_name));
    }

    public function testFindModelNotFound(): void
    {
        $this->createUser();

        $user = User::find([
            'username' => 'not_found',
        ]);

        $this->assertNull($user);
    }

    public function testFindModelUsingOneParameters(): void
    {
        $this->createUser();

        $user = User::find([
            'username' => "test",
        ]);

        $this->assertNotNull($user);
        $this->assertEquals(1, $user->id);
        $this->assertEquals("test", $user->username);
        $this->assertEquals("test@test.com", $user->email);
        $this->assertEquals("pass", $user->password);
    }

    public function testFindModelUsingMultipleParameters(): void
    {
        $this->createUser();

        $user = User::find([
            'username' => "test",
            'email' => "test@test.com",
            'password' => "pass",
        ]);

        $this->assertNotNull($user);
        $this->assertEquals(1, $user->id);
        $this->assertEquals("test", $user->username);
        $this->assertEquals("test@test.com", $user->email);
        $this->assertEquals("pass", $user->password);
    }

    public function testGetAllWhenIsEmpty(): void
    {
        $users = User::all();

        $this->assertEmpty($users);
    }

    public function testGetAll(): void
    {
        $this->createUsers(3);

        $users = User::all();

        $this->assertCount(3, $users);
        
        foreach ($users as $k => $u) {
            $this->assertEquals($k + 1, $u->id);
        }
    }

    public function testGetAllUsingParameters(): void
    {
        $this->createUsers(3);
        $user = $this->createUser();

        $this->assertCount(1, User::all(['username' => $user->username]));
    }

    public function testDelete(): void
    {
        $user = $this->createUser();

        $this->assertCount(1, User::all());

        $user->delete();

        $this->assertCount(0, User::all());
    }

    public function testWhere(): void
    {
        $user = $this->createUser();

        $user = User::where([
            ['username', '=', $user->username]
        ])->get();

        $this->assertInstanceOf(User::class, $user);
    }

    public function testWhereWithLimit(): void
    {
        $this->createUsers(5);

        $users = User::where([
            ['id', '>', '1']
        ])->limit(2)->get();

        $this->assertCount(2, $users);
    }
}