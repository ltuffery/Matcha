<?php

namespace Matcha\Api\Services;

use Firebase\JWT\JWT;
use Flight;
use Matcha\Api\Model\Notification;
use Matcha\Api\Model\User;
use Matcha\Api\Resources\NotificationsResource;

class MercurePublisher
{
    const HUB_URL = "http://localhost/.well-known/mercure";

    public function __construct(
        private string $jwtKey,
    )
    {
    }

    public function publish(string $topic, array|object $data, bool $private = false): void
    {
        $jwt = JWT::encode(['mercure' => ['publish' => ['*']]], $this->jwtKey, 'HS256');

        $body = [
            'topic' => $topic,
            'data' => json_encode($data),
        ];
        if ($private) {
            $body['private'] = 'on';
        }

        file_get_contents(self::HUB_URL, false, stream_context_create(['http' => [
            'method' => 'POST',
            'header' => "Content-type: application/x-www-form-urlencoded\r\nAuthorization: Bearer $jwt",
            'content' => http_build_query($body),
        ]]));
    }

    public function notify(User $user, array $data, bool $private = false): void
    {
        $me = Flight::user();
        $notification = Notification::to($me, $user, $data['type'], $data['message']);

        $this->publish(
            "user/" . $user->id . "/notifications",
            new NotificationsResource($notification),
            $private
        );
    }
}