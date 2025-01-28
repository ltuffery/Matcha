<?php

namespace Matcha\Api\Resources;

use Matcha\Api\Model\Like;

/**
 * @property Like $model
 */
class LikeResource extends JsonResource
{

    public function __construct(Like $model)
    {
        parent::__construct($model);
    }

    public function jsonSerialize(): array
    {
        $user = $this->model->liked();

        return [
            'avatar' => $user->getAvatar(),
            'username' => $user->username,
            'first_name' => $user->first_name,
        ];
    }
}