<?php

namespace Matcha\Api\Controllers;

use Exception;
use Flight;
use flight\net\UploadedFile;
use Matcha\Api\Exceptions\InvalidDataException;
use Matcha\Api\Model\Photo;
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
            'sexual_preferences' => 'required',
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

        $user = new User();
        $user->username = $request->data->username;
        $user->email = $request->data->email;
        $user->password = password_hash($request->data->password, PASSWORD_DEFAULT);
        $user->birthday = $request->data->birthday;
        $user->first_name = $request->data->first_name;
        $user->last_name = $request->data->last_name;
        $user->gender = $request->data->gender;
        $user->sexual_preferences = $request->data->sexual_preferences;
        $user->biography = $request->data->biography;

        $saved = $user->save();

        if ($saved) {
            if (isset($request->data->tags) && !empty($request->data->tags)) {
                $this->saveTags($saved, $request->data->tags);
            }

            if (!getenv("PHPUNIT_TEST")) {
                $this->uploadPhotos($saved);
            }

            Flight::json([
                'user' => json_encode($user),
            ], 201);
        }
    }

    private function saveTags(User $user, string $tags): void
    {
        foreach (explode(",", $tags) as $tag) {
            $user->addTag($tag);
        }
    }

    private function uploadPhotos(User $user): void
    {
        if (!is_dir(BASE_PATH . "/storage/photos")) {
            mkdir(BASE_PATH . "/storage/photos", recursive: true);
        }

        $photos = Flight::request()->getUploadedFiles()['photos'];

        if (is_array($photos)) {
            foreach (Flight::request()->getUploadedFiles()['photos'] as $file) {
                $this->savePhotos($file, $user);
            }
        } else {
            $this->savePhotos($photos, $user);
        }
    }

    private function savePhotos(UploadedFile $file, User $user): void
    {
        $name = bin2hex(random_bytes(15));

        $photo = new Photo();

        $photo->name = $name;
        $photo->user_id = $user->id;

        $photo->create();
        $file->moveTo(BASE_PATH . "/storage/photos/" . $name . ".png");
    }

}
