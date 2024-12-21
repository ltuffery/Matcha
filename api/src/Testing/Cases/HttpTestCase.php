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
    public function withHeader(array $headers): self
    {
        foreach ($headers as $name => $value) {
            $header = 'HTTP_' . strtoupper(str_replace('-', '_', $name));
            $_SERVER[$header] = $value;
        }

        return $this;
    }

    public function get(string $url, ?array $data = []): TestResponse
    {
        return $this->request('GET', $url, $data);
    }

    public function post(string $url, ?array $data = []): TestResponse
    {
        return $this->request('POST', $url, $data);
    }

    public function put(string $url, ?array $data = []): TestResponse
    {
        return $this->request('PUT', $url, $data);
    }

    public function patch(string $url, ?array $data = []): TestResponse
    {
        return $this->request('PATCH', $url, $data);
    }

    public function delete(string $url, ?array $data = []): TestResponse
    {
        return $this->request('DELETE', $url, $data);
    }

    public function request(string $method, string $url, array $data = []): TestResponse
    {
        $body = "";

        if ($method == 'GET') {
            $_GET = $data;
        } else {
            $body = json_encode($data);
        }

        Flight::response()->clear();
        $r = Flight::request()->init([
            'url' => $url,
            'method' => $method,
            'type' => 'application/json',
            'body' => $body,
            'ip' => '127.0.0.1',
        ]);

        // ob_end_clean();

        // var_dump(array_filter(Flight::router()->getRoutes(), function ($route) use ($r) {
        //     return $route->pattern == $r->url;
        // }) != null, $r->url);

        Flight::start();

        return new TestResponse();
    }
}
