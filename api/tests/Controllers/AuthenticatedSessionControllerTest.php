<?php

namespace Controllers;

use Exception;
use Flight;
use flight\util\Collection;
use Matcha\Api\Controllers\AuthenticatedSessionController;
use PDO;
use PHPUnit\Framework\TestCase;

class AuthenticatedSessionControllerTest extends TestCase
{

    private AuthenticatedSessionController $controller;

    protected function setUp(): void
    {
        Flight::response()->clearBody();
        Flight::register('db', PDO::class, ['sqlite::memory:']);

        Flight::db()->exec("CREATE TABLE IF NOT EXISTS users(
            `id` integer PRIMARY KEY AUTOINCREMENT,
            `username` varchar(255) NOT NULL UNIQUE,
            `email` varchar(255) NOT NULL UNIQUE,
            `password` text NOT NULL,
            `created_at` timestamp DEFAULT CURRENT_TIMESTAMP
        );");

        $this->controller = new AuthenticatedSessionController();
    }

    public function testLoginWithNoData()
    {
        Flight::request()->data = new Collection();

        try {
            $this->controller->store();

            $this->fail();
        } catch (Exception $e) {
            $body = Flight::response()->getBody();
            $data = json_decode($body);

            $this->assertEquals(0, $data->code);
            $this->assertEquals("username is required", $data->message);
            $this->assertEquals(400, Flight::response()->status());
        }
    }

}