<?php

namespace Matcha\Api\Controllers;

use Flight;
use Matcha\Api\Model\User;
use Matcha\Api\Resources\SearchableUserResource;

class SearchProfileController
{
    public const NUMBER_USERS_SEARCHABLE = 5;

    public function index(): void
    {
        $query = Flight::request()->query;

        if (!isset($query['q']) || strlen($query['q']) < 2) {
            Flight::json([]);

            return;
        }

        $users = User::where([
            ['username', 'LIKE', '%' . $query['q'] . '%']
        ], self::NUMBER_USERS_SEARCHABLE);

        /** @var User $me */
        $me = Flight::user();
        $users = array_filter($users, fn ($user) => !$me->isBlocking($user));

        Flight::json(
            SearchableUserResource::collection($users)
        );
    }
}
