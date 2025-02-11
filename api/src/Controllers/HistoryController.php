<?php

namespace Matcha\Api\Controllers;

use Flight;
use Matcha\Api\Resources\LikeResource;
use Matcha\Api\Resources\ViewResource;

class HistoryController
{
    public function index(): void
    {
        $likes = Flight::user()->likes();
        $views = Flight::user()->views();

        Flight::json(
            [
                "likes" => LikeResource::collection($likes),
                "views" => ViewResource::collection($views)
            ]
        );
    }
}
