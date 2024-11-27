<?php

namespace Matcha\Api\Controllers;

use Exception;
use Flight;
use InvalidDataException;
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
            'username' => 'required',
            'email' => 'required|email',
            'password' => 'required',
            'age' => 'required|number',
            'first_name' => 'required',
            'last_name' => 'required',
            'gender' => 'required',
            'sexual_preferences' => 'required',
            'biography' => 'required',
        ]);

        $request = Flight::request();

        $user = new User();
        $user->username = $request->data->username;
        $user->email = $request->data->email;
        $user->password = password_hash($request->data->password, PASSWORD_DEFAULT);
        $user->age = $request->data->age;
        $user->first_name = $request->data->first_name;
        $user->last_name = $request->data->last_name;
        $user->gender = $request->data->gender;
        $user->sexual_preferences = $request->data->sexual_preferences;
        $user->biography = $request->data->biography;

        $saved = $user->save();

        if ($saved) {
            Flight::json([
                'user' => json_encode($user),
            ], 201);
        }
    }

}