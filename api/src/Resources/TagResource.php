<?php

namespace Matcha\Api\Resources;

use Matcha\Api\Model\Tag;

/**
 * @property Tag $model
 */
class TagResource extends JsonResource
{
    public function __construct(Tag $tag)
    {
        parent::__construct($tag);
    }

    public function jsonSerialize(): mixed
    {
        return $this->model->name;
    }
}
