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
        ])->limit(self::NUMBER_USERS_SEARCHABLE)->get();

        /** @var User $me */
        $me = Flight::user();
        if (is_array($users)) {
            $users = array_filter($users, fn ($user) => !$me->isBlocking($user));
        } else {
            $users = $me->isBlocking($users) ? [] : [$users];
        }

        Flight::json(
            SearchableUserResource::collection($users)
        );
    }
}