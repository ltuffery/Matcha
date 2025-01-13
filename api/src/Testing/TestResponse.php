<?php

namespace Matcha\Api\Testing;

use Flight;
use PHPUnit\Framework\Assert;
use Throwable;

/**
 * @method void assertStatus(int $status, ?string $message = '')
 * @method void assertJson(?array $data, ?string $message = '')
 * @method void assertCount(int $n, ?string $message = '')
 * @method void assertJsonKeys(array $keys, ?string $message = '')
 */
class TestResponse
{
    public function __construct()
    {
        $_SERVER['HTTP_HOST'] = 'localhost:8000';
    }

    /**
     * Assert status code
     *
     * @param int $status
     * @param string|null $message
     * @return void
     */
    public static function assertStatus(int $status, ?string $message = ''): void
    {
        try {
            $actual = Flight::response()->status();
            Assert::assertEquals($status, $actual, $message);
        } catch (Throwable $exception) {
            Assert::fail($exception->getMessage());
        }
    }

    /**
     * Assert JSON data
     *
     * @param array<string, mixed>|null $data
     * @param string|null $message
     * @return void
     */
    public static function assertJson(?array $data = [], ?string $message = ''): void
    {
        $body = Flight::response()->getBody();
        Assert::assertJson($body, $message);

        if (!empty($data)) {
            Assert::assertEquals($data, json_decode($body, true), $message);
        }
    }

    public static function assertCount(int $n, ?string $message = ''): void
    {
        $body = Flight::response()->getBody();
        Assert::assertJson($body, $message);

        $data = json_decode($body, true);

        Assert::assertCount($n, $data, $message);
    }

    public static function assertJsonKeys(array $keys, ?string $message = ''): void
    {
        $body = Flight::response()->getBody();
        Assert::assertJson($body, $message);

        $data = json_decode($body, true);

        foreach ($keys as $key) {
            Assert::assertArrayHasKey($key, $data, $message);
        }
    }

    public function getData(): array
    {
        return json_decode(Flight::response()->getBody(), true);
    }
}
