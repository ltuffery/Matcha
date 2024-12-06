<?php

namespace Matcha\Api\Testing;

use Flight;
use PHPUnit\Framework\Assert;

class TestResponse
{
    public function __construct()
    {
        $_SERVER['HTTP_HOST'] = 'localhost:8000';
    }

    public static function assertStatus(int $status, ?string $message = ''): void
    {
        $actual = Flight::response()->status();
        Assert::assertEquals($status, $actual, $message);
    }

    public static function assertJson(?array $data = [], ?string $message = ''): void
    {
        $body = Flight::response()->getBody();
        Assert::assertJson($body, $message);

        if (!empty($data)) {
            Assert::assertContains($data, json_decode($body, true));
        }
    }

}