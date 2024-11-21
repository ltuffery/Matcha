<?php

use flight\util\Collection;
use Matcha\Api\Controllers\AuthController;
use PHPUnit\Framework\TestCase;

class AuthControllerTest extends TestCase
{

    private AuthController $controller;

    protected function setUp(): void
    {
        $this->controller = new AuthController();
    }

    public function testRegisterWithNoData()
    {
        Flight::request()->data = new Collection();

        try {
            $this->controller->register();
        } catch (Exception $e) {
            $body = Flight::response()->getBody();
            $data = json_decode($body);

            $this->assertEquals(1, $data->code);
            $this->assertEquals("username is required", $data->message);
            $this->assertEquals(400, Flight::response()->status());
        }
    }

}