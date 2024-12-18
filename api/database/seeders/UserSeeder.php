<?php

namespace Matcha\Database\Seeders;

use Matcha\Api\Model\User;

class UserSeeder implements SeederInterface
{
    public function run(): void
    {
        User::factory()->count(20)->create();
    }
}