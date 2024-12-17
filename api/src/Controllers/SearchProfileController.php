<?php

namespace Matcha\Api\Controllers;

use Flight;
use Matcha\Api\Model\User;

class SearchProfileController
{
    public function index(): void
    {
        $query = Flight::request()->query;

        if (!isset($query['q']) || strlen($query['q']) < 5) {
            Flight::json([]);

            return;
        }

        $users = User::all();
        $result = array_filter($users, fn (User $v) => str_starts_with($v->username, $query['q']));

        Flight::json(
            array_slice($result, 0, 5)
        );
    }
}