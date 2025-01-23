<?php

namespace Matcha\Database\Seeders;

use Matcha\Api\Model\Photo;
use Matcha\Api\Model\User;

class PhotoSeeder implements SeederInterface
{
    public function run(): void
    {
        $users = User::all();

        foreach ($users as $user) {
            for ($i = 0; $i < rand(1, 5); $i++) {
                $name = $this->savePhoto($user->id);

                file_put_contents(BASE_PATH . "/storage/photos/" . $name . ".png", file_get_contents(faker()->imageUrl()));
            }
        }
    }

    private function savePhoto(int $user_id): string
    {
        $photo = new Photo();

        $photo->name = bin2hex(random_bytes(15));
        $photo->user_id = $user_id;

        $photo->save();

        return $photo->name;
    }
}