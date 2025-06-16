<?php

namespace Matcha\Database\Seeders;

use Exception;

class DatabaseSeeder implements SeederInterface
{
    public function run(int $n = 1): void
    {
        $this->call([
            UserSeeder::class,
            MatchesSeeder::class,
            MessageSeeder::class,
            TagSeeder::class,
        ], $n);
    }

    private function call(array $seeders, int $n): void
    {
        foreach ($seeders as $index => $seeder) {
            $instance = new $seeder;

            echo "[\033[1;33m" . $index . "\033[0m] " . $seeder . PHP_EOL;

            for ($i = 0; $i < $n; $i++) {
                try {
                    $instance->run();
                } catch (Exception $e) {
                    echo sprintf("\033[0;31m[%s] \033[0m%s", $seeder, $e->getMessage()) . PHP_EOL;
                }
            }
        }
    }
}