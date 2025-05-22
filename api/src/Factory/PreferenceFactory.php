<?php

namespace Matcha\Api\Factory;

use Matcha\Api\Model\User;

class PreferenceFactory extends Factory
{
    protected function define(): array
    {
        $user = $this->states['user'] ?? User::factory()->create();

        return [
            "user_id" => $user->id,
            "age_maximum" => rand($user->getAge(), 60),
            "age_minimum" => rand(18, $user->getAge()),
            "sexual_preferences" => faker()->randomElement(['A', 'M', 'F', 'O']),
            "distance_maximum" => rand(1, 20),
            "by_tags" => (bool)rand(0, 1),
        ];
    }
}
