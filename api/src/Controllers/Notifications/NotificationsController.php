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
        ])->limit(50)->get(true);

        Flight::json(
            NotificationsResource::collection($notifications)
        );
    }

    public function viewed(string $id): void
    {
        $notification = Notification::find([
            'id' => intval($id),
        ]);

        if (is_null($notification) || $notification->user_id !== Flight::user()->id) {
            Flight::json([
                'message' => 'You can not update notification.',
            ], 403);
            return;
        }

        if ($notification->view) {
            Flight::json([
                'success' => true,
                'message' => 'Notification updated successfully.',
            ]);
            return;
        }

        $notification->view = true;

        $notification->save();
        Flight::json([
            'success' => true,
            'message' => 'Notification updated successfully.',
        ]);
    }
}
