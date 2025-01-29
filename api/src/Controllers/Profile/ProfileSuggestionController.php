<?php

namespace Matcha\Api\Controllers\Profile;

use Flight;
use Matcha\Api\Model\User;
use Matcha\Api\Resources\ProfileResource;

class ProfileSuggestionController
{

    /**
     * Earth radius
     */
    private const R = 6371;

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

        $users = array_filter($users, function ($value) use ($likes, $user) {
            return !in_array($value->id, $likes)
                && $this->inLocation($user->lat, $user->lon, $value->lat, $value->lon, 20);
        });

        $tags = $user->getTags();

        usort($users, function (User $a, User $b) use ($tags) {
            return count(array_diff($tags, $b->getTags())) - count(array_diff($tags, $a->getTags()));
        });

        usort($users, function (User $a, User $b) {
            return $b->fame_rating - $a->fame_rating;
        });

        Flight::json(
            ProfileResource::collection($users)
        );
    }

    private function haversine($lat1, $lon1, $lat2, $lon2): float|int
    {
        $a = pow(sin(($lat2 - $lat1) / 2), 2) + cos($lat1) * cos($lat2) * pow(sin(($lon2 - $lon1) / 2), 2);
        $c = 2 * atan2(sqrt($a), sqrt(1 - $a));

        return self::R * $c;
    }

    private function inLocation($lat1, $lon1, $lat2, $lon2, $rayon): bool
    {
        return $this->haversine($lat1, $lon1, $lat2, $lon2) <= $rayon;
    }
}