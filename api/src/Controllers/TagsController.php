<?php

namespace Matcha\Api\Controllers;

use Flight;
use Matcha\Api\Model\Tag;
use Matcha\Api\Resources\TagResource;

class TagsController
{
    public function index(): void
    {
        $tags = Tag::all();

        Flight::json(TagResource::collection($tags));
    }
}
