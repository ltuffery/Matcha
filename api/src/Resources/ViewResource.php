<?php

namespace Matcha\Api\Resources;

use Matcha\Api\Model\View;

/**
 * @property View $model
 */
class ViewResource extends JsonResource
{
    public function __construct(View $model)
    {
        parent::__construct($model);
    }

    public function jsonSerialize(): array
    {
        $user = $this->model->viewed();

        return [
            'avatar' => $user->getAvatar(),
            'username' => $user->username,
            'first_name' => $user->first_name,
            'last_name' => $user->last_name,
            'created_at' => $user->created_at,
        ];
    }
}
