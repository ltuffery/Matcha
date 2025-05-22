<?php

namespace Matcha\Api\Factory;

use Matcha\Api\Model\User;

class PhotoFactory extends Factory
{
    private bool $userFaker = false;

    protected function define(): array
    {
        $name = $this->generatePhoto();

        $user = $this->states['user'] ?? User::factory()->create();

        return [
            'user_id' => $user->id,
            'name' => $name,
        ];
    }

    private function generatePhoto(): string
    {
        if (!is_dir(BASE_PATH . "/storage/photos/")) {
            mkdir(BASE_PATH . "/storage/photos/", recursive: true);
        }

        $name = bin2hex(random_bytes(15));

        if ($this->userFaker) {
            $url = faker()->imageUrl();
        } else {
            $photos = array_values(array_diff(
                scandir(BASE_PATH . "/database/data_preset/photos/"),
                [".", ".."] // For remove "." and ".." directory (errno=21)
            ));

            $url = BASE_PATH
                . "/database/data_preset/photos/"
                . $photos[rand(0, count($photos) - 1)];
        }

        file_put_contents(
            BASE_PATH . "/storage/photos/" . $name . ".png",
            file_get_contents($url)
        );

        return $name;
    }

    public function useFaker(bool $use = true): self
    {
        $this->userFaker = $use;

        return $this;
    }
}
