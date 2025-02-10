<?php

namespace Matcha\Database\Seeders;

use Exception;
use Matcha\Api\Model\User;

class UserSeeder implements SeederInterface
{
    public function run(): void
    {
        try {
            $this->createDefaultUser();
        } catch (Exception) {}

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
        $user->birthday = faker()->dateTimeBetween('-50 years', '-18 years')->format('Y-m-d');
        $user->first_name = faker()->firstName();
        $user->last_name = faker()->lastName();
        $user->biography = faker()->sentence();

        $user->save();
    }
}