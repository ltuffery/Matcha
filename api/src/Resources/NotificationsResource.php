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
        return [
            'type' => $this->model->type,
            'content' => $this->model->content,
            'view' => $this->model->view,
            'created_at' => $this->model->created_at,
        ];
    }
}