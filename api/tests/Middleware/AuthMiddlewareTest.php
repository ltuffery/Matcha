<?php

namespace Middleware;

use Flight;
use Matcha\Api\Middleware\AuthMiddleware;
use Matcha\Api\Testing\Cases\HttpTestCase;
use PHPUnit\Framework\TestCase;

class AuthMiddlewareTest extends TestCase
{
    use HttpTestCase;

    public function setUp(): void
    {
        $_SERVER = [];
        Flight::route('POST /test-middleware', function () {
            echo 'OK';
        })->addMiddleware(AuthMiddleware::class);
    }

    public function testNoAuthorizationHeaderGiven(): void
    {
        ob_start();
        $response = $this->post('/test-middleware');
        ob_get_clean();

        $response->assertStatus(401);
    }

    public function testInvalidJWT(): void
    {
        $response = $this
            ->withHeader(['Authorization' => 'Bearer foo'])
            ->post('/test-middleware');

        $response->assertStatus(400);
    }
}