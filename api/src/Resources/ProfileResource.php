<?php

namespace Matcha\Api\Resources;

use Matcha\Api\Model\User;

/**
 * @property User $model
 */
class ProfileResource extends JsonResource
{

    public function __construct(User $model)
    {
        parent::__construct($model);
    }

    public function jsonSerialize(): array
    {
        return [
            'username' => $this->model->username,
            'avatar' => $this->model->getAvatar(),
            'photos' => $this->model->getPhotosUrl(),
            'first_name' => $this->model->first_name,
            'last_name' => $this->model->last_name,
            'age' => $this->model->getAge(),
            'biography' => $this->model->biography,
        ];
    }
}