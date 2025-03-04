<?php

namespace Matcha\Database\Seeders;

use Exception;
use Matcha\Api\Model\Photo;
use Matcha\Api\Model\Preference;
use Matcha\Api\Model\User;

class UserSeeder implements SeederInterface
{
    public function run(): void
    {
        try {
            $this->createDefaultUser();
        } catch (Exception) {}

        User::factory()
            ->has(Preference::factory())
            ->has(Photo::factory()->count(rand(1 , 5)))
            ->create();
    }

    private function createDefaultUser(): void
    {
        User::factory()
            ->has(Preference::factory())
            ->has(Photo::factory()->count(rand(1 , 5)))
            ->state([
                'username' => 'example',
            ])
            ->create();
    }
}