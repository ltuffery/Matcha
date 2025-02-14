<?php

namespace Matcha\Api\Controllers;

use Exception;
use Flight;
use flight\net\UploadedFile;
use Matcha\Api\Exceptions\InvalidDataException;
use Matcha\Api\Model\Photo;
use Matcha\Api\Model\Preference;
use Matcha\Api\Model\User;
use Matcha\Api\Validator\Validator;

class RegisterController
{
    /**
     * Register new user
     * @throws InvalidDataException
     * @throws Exception
     */
    public function store(): void
    {
        Validator::make([
            'username' => 'required|regex:[a-zA-Z0-9\.]{5,25}',
            'email' => 'required|email',
            'password' => 'required',
            'first_name' => 'required',
            'last_name' => 'required',
            'birthday' => 'required',
            'gender' => 'required',
            'biography' => 'required',
        ]);

        $request = Flight::request();

        if (count($request->getUploadedFiles()) == 0) {
            Flight::json([
                'code' => 0,
                'message' => "Photos is required.",
            ], 400);

            throw new InvalidDataException(0, 'Photos is required.');
        }


        $photos = $this->checkPhotos();

        if (!is_array($photos)) {
            Flight::json([
                'code' => 0,
                'message' => "Photos is not valid.",
            ], 400);
            return;
        }

        $user = new User();
        $user->username = $request->data->username;
        $user->email = $request->data->email;
        $user->password = password_hash($request->data->password, PASSWORD_DEFAULT);
        $user->birthday = $request->data->birthday;
        $user->first_name = $request->data->first_name;
        $user->last_name = $request->data->last_name;
        $user->gender = $request->data->gender;
        $user->biography = $request->data->biography;

        $saved = $user->save();

        if ($saved) {
            $this->createPreferences($saved);

            if (isset($request->data->tags) && !empty($request->data->tags)) {
                $this->saveTags($saved, $request->data->tags);
            }

            if (!getenv("PHPUNIT_TEST")) {
                $this->uploadPhotos($saved, $photos);
            }

            Flight::json([
                'user' => json_encode($user),
            ], 201);
        }
    }

    private function createPreferences(User $user): void
    {
        $preferences = new Preference();

        $preferences->user_id = $user->id;
        $preferences->age_maximum = $user->getAge() + 3;
        $preferences->age_minimum = max(18, $user->getAge() - 3);

        $preferences->save();
    }

    private function saveTags(User $user, string $tags): void
    {
        foreach (explode(",", $tags) as $tag) {
            $user->addTag($tag);
        }
    }

    private function checkPhotos(): array|false
    {
        if (getenv("PHPUNIT_TEST")) {
            return [];
        }

        $photos = Flight::request()->getUploadedFiles()['photos'];
        $uploadPhotos = [];

        if (!is_array($photos)) {
            $photos = [$photos];
        }

        foreach ($photos as $photo) {
            $service = \Matcha\Api\Services\Photo::new($photo);

            if (!$service->isValidExtension()) {
                return false;
            }

            $uploadPhotos[] = $service;
        }

        return $uploadPhotos;
    }

    /**
     * @param User $user
     * @param \Matcha\Api\Services\Photo[] $photos
     * @return void
     */
    private function uploadPhotos(User $user, array $photos): void
    {
        if (!is_dir(BASE_PATH . "/storage/photos")) {
            mkdir(BASE_PATH . "/storage/photos", recursive: true);
        }

        foreach ($photos as $photo) {
            $photo->save($user);
        }
    }

}
