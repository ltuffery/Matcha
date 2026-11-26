<?php

namespace Matcha\Api\Resources;

use Matcha\Api\Model\Message;
use Matcha\Api\Model\Reaction;
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

    public function jsonSerialize(): array
    {
        $user = User::find(['id' => $this->model->sender_id]);
        $reactions = Reaction::all(['message_id' => $this->model->id]);

        return [
            'id' => $this->model->id,
            'sender' => $user->username,
            'avatar' => $user->getAvatar(),
            'content' => htmlspecialchars_decode($this->model->content),
            'view' => $this->model->view,
            'reactions' => ReactionResource::collection($reactions),
            'created_at' => $this->model->created_at,
        ];
    }
}
