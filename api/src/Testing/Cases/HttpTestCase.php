<?php

namespace Matcha\Api\Testing\Cases;

use Flight;
use Matcha\Api\Testing\TestResponse;

trait HttpTestCase
{

    /**
     * Add header in request
     *
     * @param array<string, string> $headers
     * @return static
     */
    public function withHeader(array $headers): static
    {
        foreach ($headers as $name => $value) {
            $header = 'HTTP_' . strtoupper(str_replace('-', '_', $name));
            $_SERVER[$header] = $value;
        }

        return $this;
    }

    public function post(string $url, ?array $data = []): TestResponse
    {
        Flight::request()->init([
            'url' => $url,
            'method' => 'POST',
            'type' => 'application/json',
            'body' => json_encode($data),
        ]);

        Flight::start();

        return new TestResponse();
    }
}