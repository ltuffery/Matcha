<?php

use Matcha\Api\Model\User;
use Matcha\Api\Validator\Validator;

class RegisterController {

    /**
     * Register new user
     */
    public function store()
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
            'bio' => 'required',
        ]);

        $request = Flight::request();

        $user = new User();
        $user->username = $request->data->username;
        $user->email = $request->data->email;
        $user->password = password_hash($request->data->password, PASSWORD_DEFAULT);
        $user->age = $request->data->age;
        $user->firstName = $request->data->first_name;
        $user->lastName = $request->data->last_name;
        $user->gender = $request->data->gender;
        $user->sexualPreferences = $request->data->sexual_preferences;
        $user->bio = $request->data->bio;

        $saved = $user->save();

        Flight::json([
            'user' => json_encode($user),
        ], $saved ? 201 : 400);
    }

}