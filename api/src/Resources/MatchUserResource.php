<?php

namespace Matcha\Api\Resources;

use Flight;
use Matcha\Api\Model\Message;
use Matcha\Api\Model\User;

/**
 * @property User $model
 */
class MatchUserResource extends JsonResource
{
    public function __construct(User $user)
    {
        parent::__construct($user);
    }

    public function jsonSerialize(): mixed
    {
        $resource = [
            'username' => $this->model->username,
            'first_name' => $this->model->first_name,
            'last_name' => $this->model->last_name,
            'online' => $this->model->online,
        ];
        $last_message = Message::lastOf($this->model, Flight::user());

        $resource['last_message'] = !is_null($last_message) ? new MessageResource($last_message) : null;

        return $resource;
    }
}
