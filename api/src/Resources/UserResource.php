<?php

namespace Matcha\Api\Resources;

use Matcha\Api\Model\User;

/**
 * @property User $model
 */
class UserResource extends JsonResource
{
    public function __construct(User $user)
    {
        parent::__construct($user);
    }

    public function jsonSerialize(): mixed
    {
        $excludeFields = [
            'password',
            'email_verified',
            'temporary_email_token',
            'lat',
            'lon',
        ];
        $data = $this->model->getData();

        foreach ($excludeFields as $field) {
            unset($data[$field]);
        }

        $data['avatar'] = $this->model->getAvatar();

        return $data;
    }
}