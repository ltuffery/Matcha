<?php

namespace Matcha\Api\Resources;

use Matcha\Api\Model\Photo;

class PhotoResource extends JsonResource
{
    public function __construct(Photo $model)
    {
        parent::__construct($model);
    }

    public function jsonSerialize(): mixed
    {
        return [
            'id' => $this->model->id,
            'name' => $this->model->name,
        ];
    }
}