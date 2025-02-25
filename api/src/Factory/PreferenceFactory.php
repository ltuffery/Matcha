<?php

namespace Matcha\Api\Factory;

use Matcha\Api\Model\User;

class PreferenceFactory extends Factory
{
    protected function define(): array
    {
        $newUser = User::factory()->create();

        return [
            "user_id" => $newUser->id,
            "age_maximum" => rand($newUser->getAge(), 60),
            "age_minimum" => rand(18, $newUser->getAge()),
            "sexual_preferences" => faker()->randomElement(['A', 'M', 'F', 'O']),
            "distance_maximum" => rand(1, 20),
            "by_tags" => (bool)rand(0, 1),
        ];
    }
}
