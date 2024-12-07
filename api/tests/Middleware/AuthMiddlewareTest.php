<?php

namespace Middleware;

use Flight;
use Matcha\Api\Middleware\AuthMiddleware;
use Matcha\Api\Testing\Cases\HttpTestCase;
use Matcha\Api\Testing\TestResponse;
use PHPUnit\Framework\TestCase;

class AuthMiddlewareTest extends TestCase
{
    use HttpTestCase;

    private AuthMiddleware $middleware;
    private TestResponse $response;

    public function setUp(): void
    {
        $_SERVER = [];
        $this->middleware = new AuthMiddleware();
        $this->response = new TestResponse();

        Flight::response()->clear();
        ob_start();
    }

    public function tearDown(): void
    {
        ob_end_clean();
    }

    public function testNoAuthorizationHeaderGiven(): void
    {
        $this->middleware->before([]);

        $this->response->assertStatus(401);
        $this->response->assertJson(['message' => "Unauthorized"]);
    }

    public function testInvalidJWT(): void
    {
        $_SERVER['HTTP_AUTHORIZATION'] = 'Bearer foo';

        $this->middleware->before([]);

        $this->response->assertStatus(400);
        $this->response->assertJson(['message' => "Wrong number of segments"]);
    }
}