<?php

use Matcha\Api\Exceptions\UniqueConstraindException;
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
        $this->setUpDatabase();
    }

    public function tearDown(): void
    {
        Flight::response()->clear();
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
                'sexual_preferences' => 'F',
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
            'age' => 19,
            'first_name' => 'John',
            'last_name' => 'Doe',
            'gender' => 'M',
            'sexual_preferences' => 'F',
            'biography' => 'Lorem lorem',
        ]);

        $response->assertStatus(201);
    }

    public function testWithEmailAlreadyExist(): void
    {
        /** @var User */
        $user = User::factory()->create()[0];

        $this->expectException(UniqueConstraindException::class);

        $response = $this->post('/auth/register', [
            'username' => "teste",
            'email' => $user->email,
            'password' => "password",
            'age' => 19,
            'first_name' => 'John',
            'last_name' => 'Doe',
            'gender' => 'M',
            'sexual_preferences' => 'F',
            'biography' => 'Lorem lorem',
        ]);

        $response->assertStatus(400);
    }
}