<?php

namespace Matcha\Database\Seeders;

class DatabaseSeeder implements SeederInterface
{
    public function run(): void
    {
        $this->call([
            UserSeeder::class,
            MatchesSeeder::class,
            MessageSeeder::class,
            PhotoSeeder::class,
            TagSeeder::class,
        ]);
    }

    private function call(array $seeders)
    {
        foreach ($seeders as $seeder) {
            $instance = new $seeder;

            $instance->run();
        }
    }
}