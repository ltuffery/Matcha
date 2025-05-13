<?php

use Matcha\Api\Exceptions\UniqueConstraintException;
use Matcha\Api\Model\Tag;
use Matcha\Api\Model\User;
use Matcha\Api\Testing\Cases\DatabaseTestCase;
use Matcha\Api\Testing\Cases\HttpTestCase;
use PHPUnit\Framework\TestCase;

class RegisterControllerTest extends TestCase
{
    use HttpTestCase;
    use DatabaseTestCase;

    public function setUp(): void
    {
        $_FILES['photos'] = [
            'name' => 'test.png',
            'type' => 'image/png',
            'size' => 123,
            'tmp_name' => '/tmp/php123',
            'error' => 0
        ];

        $this->setUpDatabase();
        $this->fillTagsTable();
    }

    public function tearDown(): void
    {
        $_FILES = [];

        Flight::response()->clear();
    }

    private function fillTagsTable(): void
    {
        $tags = json_decode(file_get_contents(BASE_PATH."/database/data_preset/tags.json"));

        foreach ($tags as $tag){
            $newTag = new Tag();
            $newTag->name = $tag;
            $newTag->save();
        }
    }

    public function testWithEmptyRequest(): void
    {
        try {
            $this->post('/auth/register', []);

            $this->fail();
        } catch (\Exception) {
            $this->assertEquals(400, Flight::response()->status());
            ob_end_clean();
        }
    }

    public function testWithBadUsername(): void
    {
        try {
            $this->post('/auth/register', [
                'username' => "test",
                'email' => "test@test.com",
                'password' => "password",
                'age' => 19,
                'first_name' => 'John',
                'last_name' => 'Doe',
                'gender' => 'M',
                'biography' => 'Lorem lorem',
            ]);

            $this->fail();
        } catch (Exception) {
            $this->assertEquals(400, Flight::response()->status());
            ob_end_clean();
        }
    }

    public function testWithValidData(): void
    {
        $response = $this->post('/auth/register', [
            'username' => "teste",
            'email' => "test@test.com",
            'password' => "password",
            'birthday' => "2004-12-04",
            'first_name' => 'John',
            'last_name' => 'Doe',
            'gender' => 'M',
            'biography' => 'Lorem lorem',
            'tags' => 'Netflix,Bike',
        ]);

        $response->assertStatus(201);
    }

    public function testWithEmailAlreadyExist(): void
    {
        /** @var User $user */
        $user = User::factory()->create();

        $this->expectException(UniqueConstraintException::class);

        $response = $this->post('/auth/register', [
            'username' => "teste",
            'email' => $user->email,
            'password' => "password",
            'birthday' => "2004-12-04",
            'first_name' => 'John',
            'last_name' => 'Doe',
            'gender' => 'M',
            'biography' => 'Lorem lorem',
            'tags' => 'Netflix,Bike',
        ]);

        $response->assertStatus(400);
    }

    public function testPreferencesIsCreated(): void
    {
        $response = $this->post('/auth/register', [
            'username' => "teste",
            'email' => 'email@test.com',
            'password' => "password",
            'birthday' => "2004-12-04",
            'first_name' => 'John',
            'last_name' => 'Doe',
            'gender' => 'M',
            'biography' => 'Lorem lorem',
            'tags' => 'Netflix,Bike',
        ]);

        $response->assertStatus(201);

        $user = User::find([
            'username' => 'teste',
        ]);

        $this->assertNotNull($user);

        $preferences = $user->getPreferences();

        $this->assertNotNull($preferences);
        $this->assertEquals('A', $preferences->sexual_preferences);
        $this->assertEquals(18, $preferences->age_minimum);
        $this->assertEquals(24, $preferences->age_maximum);
        $this->assertTrue($preferences->by_tags);
    }
}