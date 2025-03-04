<?php

namespace Matcha\Api\Controllers\Profile;

use Flight;
use Matcha\Api\Model\User;
use Matcha\Api\Model\UserTag;
use Matcha\Api\Resources\ProfileResource;
use Matcha\Api\Services\Photo;

class ProfileController
{
    public function index(): void
    {
        Flight::json(
            new ProfileResource(Flight::user())
        );
    }

    /**
     * @throws \ReflectionException
     */
    public function show(string $username): void
    {
        $user = User::find(["username" => $username]);

        if (is_null($user)) {
            Flight::json(["message" => "Not found."], 404);
            return;
        }

        Flight::json(
            new ProfileResource($user)
        );
    }

    public function update(): void
    {
        /** @var User $user */
        $user = Flight::user();

        foreach (Flight::request()->data as $key => $value) {
            if (!isset($user->{$key})) {

                if ($key == 'photos' && !is_null($value)) {
                    $this->savePhotos($user, $value);
                } elseif ($key == 'tags') {
                    UserTag::deleteAllFromUser($user);

                    if (!empty($value)) {
                        foreach ($value as $tag) {
                            $user->addTag($tag);
                        }
                    }
                }

                continue;
            }

            $user->{$key} = $value;
        }

        $user->save();
    }

    private function savePhotos(User $user, $value): void
    {
        $photos = [];
        $myPhotos = $user->getPhotosUrl();

        foreach ($value as $photo) {
            if (isset($photo['file']) && !is_null($photo['file'])) {
                unset($myPhotos[array_search($photo['url'], $myPhotos)]);
            }
        }

        $files = Flight::request()->files;
        if (isset($files['photos'])) {
            $files = $files['photos'];
        }

        if (isset($files['name'])) {
            foreach ($files['name'] as $index => $photo) {
                $uploadedFile = new \flight\net\UploadedFile(
                    $files['name'][$index]['file'],
                    $files['type'][$index]['file'],
                    $files['size'][$index]['file'],
                    $files['tmp_name'][$index]['file'],
                    $files['error'][$index]['file'],
                );

                var_dump($uploadedFile);
                $p = Photo::new($uploadedFile);

                if (!$p->isValidExtension()) {
                    Flight::json(["message" => "Invalid file type."], 400);
                    return;
                }

                $photos[] = $p;
            }
        }

        foreach ($myPhotos as $photo) {
            $split = explode('/', $photo);
            $find = \Matcha\Api\Model\Photo::find([
                'name' => end($split),
            ]);

            $find->delete();
        }

        foreach ($photos as $photo) {
            $photo->save($user);
        }
    }

    public function destroy(): void
    {
        Flight::user()->delete();

        Flight::json([], 203);
    }

    public function offline(): void
    {
        $user = Flight::user();

        $user->last_connection = date('Y-m-d H:i:s');

        $user->save();
    }
}
