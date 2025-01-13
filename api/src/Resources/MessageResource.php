<?php

namespace Matcha\Api\Resources;

use Matcha\Api\Model\Message;
use Matcha\Api\Model\User;

/**
 * @property Message $model
 */
class MessageResource extends JsonResource
{
    public function __construct(Message $message)
    {
        parent::__construct($message);
    }

    public function jsonSerialize(): mixed
    {
        return [
            'sender' => new UserResource(User::find(['id' => $this->model->sender_id])),
            'content' => $this->model->content,
            'view' => $this->model->view,
            'created_at' => $this->model->created_at,
        ];
    }
}