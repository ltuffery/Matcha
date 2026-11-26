<?php

namespace Matcha\Api\Resources;

use Flight;
use Matcha\Api\Model\Reaction;
use Matcha\Api\Model\User;

/**
 * @property Reaction $model
 */
class ReactionResource extends JsonResource
{
    public function __construct(Reaction $reaction)
    {
        parent::__construct($reaction);
    }

    public function jsonSerialize(): mixed
    {
        $user = User::find(['id' => $this->model->user_id]);

        return [
            'username' => $user->username,
            'message_id' => $this->model->message_id,
            'emoji' => $this->model->emoji,
            'is_me' => Flight::user()->id == $this->model->user_id,
        ];
    }
}