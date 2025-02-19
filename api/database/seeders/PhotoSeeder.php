<?php

namespace Matcha\Database\Seeders;

use Matcha\Api\Model\Photo;
use Matcha\Api\Model\User;

class PhotoSeeder implements SeederInterface
{
    private array $photos = [];
    private int $count = 0;

    public function __construct()
    {
        $this->photos = scandir(dirname(__DIR__) . "/data_preset/photos/");
        $this->count = count($this->photos);
    }

    public function run(): void
    {
        $users = User::all();

        foreach ($users as $user) {
            if (count($user->getPhotosUrl()) >= 5) {
                continue;
            }

            for ($i = 0; $i < rand(1, (5 - count($user->getPhotosUrl()))); $i++) {
                $this->savePhoto($user->id);
            }
        }
    }

    private function savePhoto(int $user_id): void
    {
        $file = $this->photos[rand(0, $this->count - 1)];

        if (!is_file(dirname(__DIR__) . "/data_preset/photos/" . $file)) {
            return;
        }

        $photo = new Photo();

        $photo->name = bin2hex(random_bytes(15));
        $photo->user_id = $user_id;

        file_put_contents(
            BASE_PATH . "/storage/photos/" . $file,
            file_get_contents(dirname(__DIR__) . "/data_preset/photos/" . $file)
        );

        $photo->save();
    }
}