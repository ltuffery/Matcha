<?php

namespace Middleware;

use Firebase\JWT\JWT;
use Flight;
use Matcha\Api\Middleware\AuthMiddleware;
use Matcha\Api\Testing\Cases\HttpTestCase;
use Matcha\Api\Testing\TestResponse;
use Tests\MatchaTestCase;

class AuthMiddlewareTest extends MatchaTestCase
{
    private AuthMiddleware $middleware;
    private TestResponse $response;

    public function setUp(): void
    {
        $_SERVER = [];
        $this->middleware = new AuthMiddleware();
        $this->response = new TestResponse();

        // ob_start();
    }

    public function testNoAuthorizationHeaderGiven(): void
    {
        $this->middleware->before([]);

        $this->response->assertStatus(401);
        $this->response->assertJson(['message' => "Unauthorized"]);
    }

    public function testInvalidJWTSegments(): void
    {
        $_SERVER['HTTP_AUTHORIZATION'] = 'Bearer foo';

        $this->middleware->before([]);

        $this->response->assertStatus(401);
        $this->response->assertJson(['message' => "Unauthorized"]);
    }

    public function testInvalidJWTSignatureVerificationFailed(): void
    {
        $_SERVER['HTTP_AUTHORIZATION'] = 'Bearer eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9.eyJzdWIiOiIxMjM0NTY3ODkwIiwibmFtZSI6IkpvaG4gRG9lIiwiaWF0IjoxNTE2MjM5MDIyfQ.SflKxwRJSMeKKF2QT4fwpMeJf36POk6yJV_adQssw5c';

        $this->middleware->before([]);

        $this->response->assertStatus(401);
        $this->response->assertJson(['message' => "Unauthorized"]);
    }

    public function testValidJWT(): void
    {
        $token = JWT::encode(['username' => 'John'], getenv('SECRET_KEY'), 'HS256');
        $_SERVER['HTTP_AUTHORIZATION'] = 'Bearer ' . $token;

        $this->middleware->before([]);

        $this->response->assertStatus(200);
    }
}