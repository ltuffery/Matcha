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

        $users = User::where([
            ['id', '<>', $user->id],
            ['sexual_preferences', 'IN', "('A', '" . $user->gender . "')"],
            $sexualPreference,
        ]);
        $likes = array_map(fn ($like) => $like->liked_id, $user->likes());

        $users = array_filter($users, function ($value) use ($likes) {
            return !in_array($value->id, $likes);
        });

        Flight::json(
            ProfileResource::collection($users)
        );
    }
}