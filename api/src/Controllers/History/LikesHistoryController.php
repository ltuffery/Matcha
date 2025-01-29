<?php

namespace Matcha\Api\Controllers\History;

use Flight;
use Matcha\Api\Resources\LikeResource;

class LikesHistoryController
{
    public function index(): void
    {
        $likes = Flight::user()->likes();

        Flight::json(
            LikeResource::collection($likes)
        );
    }
}
