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
}