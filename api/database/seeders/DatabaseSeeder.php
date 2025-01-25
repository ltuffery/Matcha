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
        foreach ($seeders as $index => $seeder) {
            $instance = new $seeder;

            echo "[\033[1;33m" . $index . "\033[0m] " . $seeder . PHP_EOL;

            $instance->run();
        }
    }
}