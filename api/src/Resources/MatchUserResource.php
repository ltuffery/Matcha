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
            'avatar' => $this->model->getAvatar(),
            'username' => $this->model->username,
            'first_name' => $this->model->first_name,
        ];
        $last_message = Message::lastOf($this->model, Flight::user());

        $resource['last_message'] = !is_null($last_message) ? new MessageResource($last_message) : null;
        $resource['unread'] = Message::countUnreadOf(Flight::user(), $this->model);

        return $resource;
    }
}
