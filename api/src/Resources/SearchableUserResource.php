<?php

namespace Matcha\Api\Resources;

use Matcha\Api\Model\User;

/**
 * @property User $model
 */
class SearchableUserResource extends JsonResource
{
    public function __construct(User $user)
    {
        parent::__construct($user);
    }

    public function jsonSerialize(): array
    {
        return [
            'username' => $this->model->username,
            'avatar' => $this->model->getAvatar(),
            'first_name' => $this->model->first_name,
            'last_name' => $this->model->last_name,
            'online' => $this->model->online,
        ];
    }
}
