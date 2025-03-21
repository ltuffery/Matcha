<?php

namespace Matcha\Api\Controllers\Profile;

use Flight;
use Matcha\Api\Model\User;
use Matcha\Api\Resources\ProfileResource;
use ReflectionException;

class ProfileSuggestionController
{
    public function index(): void
    {
        /** @var User $user */
        $user = Flight::user();

        $users = $this->getSuggestions($user);

        $preferences = $user->getPreferences();


        $users = array_filter($users, function ($value) use ($user, $preferences) {
            $userPreferences = $value->getPreferences();
            return $this->inLocation($preferences->lat, $preferences->lon, $userPreferences->lat, $userPreferences->lon, $preferences->distance_maximum)
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

    /**
     * @return User[]
     * @throws ReflectionException
     */
    private function getSuggestions(User $user): array
    {
        $preferences = $user->getPreferences();

        $sexualPreference = $preferences->sexual_preferences == 'A' ?
            "users.gender IN ('M', 'F', 'O')" :
            "users.gender = '$preferences->sexual_preferences'";

        $users = User::where([
            ['users.gender', 'IN', "('M', 'F', 'O')"]
        ])
            ->join('preferences', 'preferences.user_id', '=', 'users.id')
            ->join('preferences', 'preferences.sexual_preferences', '=', $user->gender) // TODO: IN ('A', '$user->gender')
            ->join('likes', 'likes.user_id', '=', 'users.id')
            ->groupBy('users.username')
            ->get();

//        $stmt = Flight::db()->prepare("
//        SELECT
//            users.*
//        FROM users
//                 INNER JOIN preferences
//                            ON users.id = preferences.user_id
//                                AND preferences.sexual_preferences IN ('A', '$user->gender')
//                 INNER JOIN likes
//                            ON 1 <> likes.liked_id
//                                AND likes.user_id = users.id
//        WHERE $sexualPreference
//        GROUP BY users.username;
//        ");

//        $stmt->execute();

//        return array_map(fn ($data) => User::morph($data), $stmt->fetchAll());

        return $users;
    }

    private function inLocation($lat1, $lon1, $lat2, $lon2, $rayon): bool
    {
        return haversine($lat1, $lon1, $lat2, $lon2) <= $rayon;
    }
}
