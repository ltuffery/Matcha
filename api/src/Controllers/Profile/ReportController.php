<?php

namespace Matcha\Api\Controllers\Profile;

use Flight;
use Matcha\Api\Exceptions\UniqueConstraintException;
use Matcha\Api\Model\User;
use Matcha\Api\Validator\Validator;

class ReportController
{
    public function store(string $username): void
    {
        Validator::make([
            'raison' => 'required',
        ]);

        $user = User::find([
            'username' => $username,
        ]);
        $me = Flight::user();

        if (is_null($user) || $me->username === $username) {
            Flight::json([
                'message' => 'Forbidden',
            ], 403);
            return;
        }

        try {
            $me->report($user, Flight::request()->data->raison);
        } catch (UniqueConstraintException) {}

        Flight::json([
            'success' => true,
        ], 201);
    }
}