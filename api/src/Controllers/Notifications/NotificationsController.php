<?php

namespace Matcha\Api\Controllers\Notifications;

use Flight;
use Matcha\Api\Model\Notification;
use Matcha\Api\Resources\NotificationsResource;

class NotificationsController
{
    public function index(): void
    {
        $notifications = Notification::where([
            ['user_id', '=', Flight::user()->id],
        ], 50);

        Flight::json(
            NotificationsResource::collection($notifications)
        );
    }

    public function viewed(int $id): void
    {
        $notification = Notification::find([
            'id' => $id,
        ]);

        if ($notification->user_id !== Flight::user()->id) {
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