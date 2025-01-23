<?php

namespace Matcha\Api\Controllers;

use Flight;
use Matcha\Api\Model\User;
use Matcha\Api\Resources\SearchableUserResource;

class SearchProfileController
{
    public function index(): void
    {
        $query = Flight::request()->query;

        if (!isset($query['q']) || strlen($query['q']) < 2) {
            Flight::json([]);

            return;
        }

        $users = User::where([
            ['username', 'LIKE', '%' . $query['q'] . '%']
        ], 5);

        Flight::json(
            SearchableUserResource::collection($users)
        );
    }
}
