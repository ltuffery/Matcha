<?php

namespace Matcha\Api\Resources;

use Matcha\Api\Model\Notification;

/**
 * @property Notification $model
 */
class NotificationsResource extends JsonResource
{

    public function jsonSerialize(): array
    {
        $user = $this->model->sender();

        return [
            'type' => $this->model->type,
            'data' => [
                'username' => $user->username,
                'avatar' => $user->getAvatar(),
                'content' => $this->model->content,
                'view' => $this->model->view,
                'created_at' => $this->model->created_at,
            ],
        ];
    }
}