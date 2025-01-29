<?php

namespace Matcha\Api\Controllers\Profile;

use Flight;
use Matcha\Api\Model\User;
use Matcha\Api\Resources\ProfileResource;

class ProfileSuggestionController
{

    public function index(): void
    {
        /** @var User $user */
        $user = Flight::user();

        $sexualPreference = $user->sexual_preferences == 'A' ?
            ['gender', '<', "('M', 'F', 'O')"] :
            ['gender', '=', $user->sexual_preferences];

        Flight::json(
            ProfileResource::collection(
                User::where([
                    ['id', '<>', $user->id],
                    ['sexual_preferences', 'IN', "('A', '" . $user->gender . "')"],
                    $sexualPreference,
                ])
            )
        );
    }
}