<?php

use flight\util\Collection;
use Matcha\Api\Controllers\AuthController;
use PHPUnit\Framework\TestCase;

class AuthControllerTest extends TestCase
{

    private AuthController $controller;

    protected function setUp(): void
    {
        Flight::response()->clear();
        Flight::register('db', PDO::class, ['sqlite::memory:']);

        Flight::db()->exec("CREATE TABLE IF NOT EXISTS users(
            `id` integer PRIMARY KEY AUTOINCREMENT,
            `username` varchar(255) NOT NULL UNIQUE,
            `email` varchar(255) NOT NULL UNIQUE,
            `password` text NOT NULL,
            `created_at` timestamp DEFAULT CURRENT_TIMESTAMP
        );");

        $this->controller = new AuthController();
    }

    public function testRegisterWithNoData()
    {
        Flight::request()->data = new Collection();

        try {
            $this->controller->register();

            $this->fail();
        } catch (Exception $e) {
            $body = Flight::response()->getBody();
            $data = json_decode($body);

            $this->assertEquals(1, $data->code);
            $this->assertEquals("username is required", $data->message);
            $this->assertEquals(400, Flight::response()->status());
        }
    }

    public function testLoginWithNoData()
    {
        Flight::request()->data = new Collection();

        try {
            $this->controller->login();

            $this->fail();
        } catch (Exception $e) {
            $body = Flight::response()->getBody();
            $data = json_decode($body);

            $this->assertEquals(1, $data->code);
            $this->assertEquals("username is required", $data->message);
            $this->assertEquals(400, Flight::response()->status());
        }
    }

    public function testLoginWithValidData()
    {
        Flight::request()->data = new Collection([
            'username' => 'test',
            'password' => 'password'
        ]);

        $stmt = Flight::db()->prepare("INSERT INTO users VALUES (:id, :username, :email, :password, :created_at)");

        $stmt->execute([
            'id' => 1,
            'username' => 'test',
            'email' => 'test@test.com',
            'password' => password_hash("password", PASSWORD_DEFAULT),
            'created_at' => time()
        ]);

        $this->controller->login();

        $data = json_decode(Flight::response()->getBody());

        $this->assertTrue($data->success);
    }

}