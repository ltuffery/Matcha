<?php

namespace Matcha\Database\Seeders;

use Matcha\Api\Model\Preference;
use Matcha\Api\Model\User;

class PreferencesSeeder implements SeederInterface
{
    public function run(): void
    {
        foreach (User::all() as $user) {
            $preferences = new Preference();

            $preferences->user_id = $user->id;
            $preferences->age_maximum = rand($user->getAge(), 60);
            $preferences->age_minimum = rand(18, $user->getAge());
            $preferences->sexual_preferences = faker()->randomElement(['A', 'M', 'F', 'O']);
            $preferences->distance_maximum = rand(1, 20);
            $preferences->by_tags = (bool)rand(0, 1);

            $preferences->save();
        }
    }
}