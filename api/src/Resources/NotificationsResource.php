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
            'id' => $this->model->id,
            'type' => $this->model->type,
            'data' => [
                'username' => $user->username,
                'first_name' => $user->first_name,
                'last_name' => $user->last_name,
                'avatar' => $user->getAvatar(),
                'content' => $this->model->content,
                'view' => $this->model->view,
                'created_at' => $this->model->created_at,
            ],
        ];
    }
}
