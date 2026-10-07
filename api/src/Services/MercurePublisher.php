<?php

namespace Matcha\Api\Services;

use Firebase\JWT\JWT;

class MercurePublisher
{
    const HUB_URL = "http://localhost/.well-known/mercure";

    public function __construct(
        private string $jwtKey,
    ) {}

    public function publish(string $topic, array $data, bool $private = false): void
    {
        $jwt = JWT::encode(['mercure' => ['publish' => ['*']]], $this->jwtKey, 'HS256');

        $body = [
            'topic' => $topic,
            'data'  => json_encode($data),
        ];
        if ($private) {
            $body['private'] = 'on';
        }

        file_get_contents(self::HUB_URL, false, stream_context_create(['http' => [
            'method'  => 'POST',
            'header'  => "Content-type: application/x-www-form-urlencoded\r\nAuthorization: Bearer $jwt",
            'content' => http_build_query($body),
        ]]));
    }
}