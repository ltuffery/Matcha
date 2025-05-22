<?php

namespace Matcha\Database\Seeders;

use Matcha\Api\Model\User;

class MatchesSeeder implements SeederInterface
{
    public function run(): void
    {
        $users = User::all();

        for ($i = 0; $i < count($users); $i++) {
            foreach (faker()->randomElements($users, rand(0, count($users) / 2)) as $user) {
                try {
                    if ($user->id === $users[$i]->id) {
                        continue;
                    }

                    $users[$i]->like($user);
                    $user->like($users[$i]);
                } catch (\Exception) {
                }
            }
        }
    }
}