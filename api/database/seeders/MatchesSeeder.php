<?php

namespace Matcha\Database\Seeders;

use Matcha\Api\Model\User;

class MatchesSeeder implements SeederInterface
{
    public function run(): void
    {
        $users = User::all();

        for ($i = 0; $i < count($users); $i++) {
            if (rand(0, 1)) {
                $user = faker()->randomElement($users);

                if ($user->id == $i + 1) {
                    continue;
                }

                try {
                    $users[$i]->like($user);
                    $user->like($users[$i]);
                } catch (\Exception) {}
            }
        }
    }
}