<?php

namespace Controllers;

use Exception;
use Flight;
use flight\util\Collection;
use Matcha\Api\Controllers\AuthenticatedSessionController;
use Matcha\Api\Testing\Cases\DatabaseTestCase;
use PHPUnit\Framework\TestCase;

class AuthenticatedSessionControllerTest extends TestCase
{

    use DatabaseTestCase {
        DatabaseTestCase::setUp as setUpDatabase;
    }

    private AuthenticatedSessionController $controller;

    protected function setUp(): void
    {
        $this->controller = new AuthenticatedSessionController();

        Flight::response()->clearBody();
        $this->setUpDatabase();
    }

    public function testLoginWithNoData()
    {
        Flight::request()->data = new Collection();

        try {
            $this->controller->store();

            $this->fail();
        } catch (Exception) {
            $body = Flight::response()->getBody();
            $data = json_decode($body);

            $this->assertEquals(0, $data->code);
            $this->assertEquals("username is required", $data->message);
            $this->assertEquals(400, Flight::response()->status());
        }
    }

}