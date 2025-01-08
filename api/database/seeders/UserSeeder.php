<?php

namespace Matcha\Database\Seeders;

use Matcha\Api\Model\User;

class UserSeeder implements SeederInterface
{
    public function run(): void
    {
        $this->createDefaultUser();

        User::factory()->count(20)->create();
    }

    private function createDefaultUser(): void
    {
        $user = new User();

        $user->username = "example";
        $user->email = "example@example.com";
        $user->password = password_hash('password', PASSWORD_DEFAULT);
        $user->email_verified = true;
        $user->gender = 'M';
        $user->sexual_preferences = 'F';
        $user->age = 21;
        $user->first_name = faker()->firstName();
        $user->last_name = faker()->lastName();
        $user->biography = faker()->sentence();

        $user->save();
    }
}