<?php

namespace Matcha\Api\Controllers\Notifications;

use Flight;
use Matcha\Api\Model\Notification;
use Matcha\Api\Model\User;
use Matcha\Api\Resources\NotificationsResource;
use Matcha\Api\Validator\Validator;

class NotificationsController
{
    private array $contents = [
        NotificationType::MATCH => '{name} has matched you',
        NotificationType::LIKE => '{name} has liked you',
        NotificationType::UNLIKE => '{name} has unliked you',
        NotificationType::VIEW => '{name} has viewed you',
        NotificationType::MESSAGE => '{name} sent you a message',
    ];

    public function index(): void
    {
        $notifications = Notification::where([
            ['user_id', '=', Flight::user()->id],
        ])->limit(50)->get();

        Flight::json(
            NotificationsResource::collection($notifications)
        );
    }

    public function store(string $username): void
    {
        Validator::required([
            'type',
        ]);

        $me = Flight::user();
        $request = Flight::request();

        if ($request->data->type == NotificationType::UNLIKE
            && !$me->hasMatch($username)) {
            Flight::json([
                'message' => 'Forbidden',
            ], 403);
            return;
        }

        $user = User::find(['username' => $username]);

        if ($request->data->type == NotificationType::LIKE && $me->likedBy($user)) {
            $request->data->type = NotificationType::MATCH;
        }

        $notification = Notification::to(
            $me,
            $user,
            $request->data->type,
            str_replace('{name}', $user->first_name, $this->contents[strtoupper($request->data->type)]),
        );

        Flight::json(new NotificationsResource($notification), 201);
    }

    public function viewed(int $id): void
    {
        $notification = Notification::find([
            'id' => $id,
        ]);

        if (is_null($notification) || $notification->user_id !== Flight::user()->id) {
            Flight::json([
                'message' => 'You can not update notification.',
            ], 403);
            return;
        }

        if ($notification->view) {
            Flight::json([], 204);
            return;
        }

        $notification->view = true;

        $notification->save();
        Flight::json([], 204);
    }
}
