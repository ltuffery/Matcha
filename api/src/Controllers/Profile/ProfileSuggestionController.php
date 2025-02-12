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

        $preferences = $user->getPreferences();
        $sexualPreference = $preferences->sexual_preferences == 'A' ?
            ['gender', '<', "('M', 'F', 'O')"] :
            ['gender', '=', $preferences->sexual_preferences];

        $users = User::where([
            ['id', '<>', $user->id],
            ['sexual_preferences', 'IN', "('A', '" . $user->gender . "')"],
            $sexualPreference,
        ]);
        $likes = array_map(fn ($like) => $like->liked_id, $user->likes());

        $users = array_filter($users, function ($value) use ($likes, $user) {
            return !in_array($value->id, $likes)
                && $this->inLocation($user->lat, $user->lon, $value->lat, $value->lon, 20)
                && !$user->isBlocking($value);
        });

        $tags = $user->getTags();

        usort($users, function (User $a, User $b) use ($tags) {
            return count(array_diff($tags, $b->getTags())) - count(array_diff($tags, $a->getTags()));
        });

        Flight::json(
            ProfileResource::collection($users)
        );
    }

    private function inLocation($lat1, $lon1, $lat2, $lon2, $rayon): bool
    {
        return haversine($lat1, $lon1, $lat2, $lon2) <= $rayon;
    }
}
