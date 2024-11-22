<?php

namespace Matcha\Api\Controllers;

use Flight;
use InvalidDataException;
use Matcha\Api\Model\User;
use Matcha\Api\Validator\Validator;
use ReflectionException;

class EmailController
{
    /**
     * @throws InvalidDataException
     * @throws ReflectionException
     */
    public function emailVerif(): void
    {
        Validator::make([
            'email' => 'required|email',
        ]);

        $request = Flight::request();

        $user = User::find([
            'email' => $request->data->email,
        ]);

        if ($user == null) {
            Flight::json([
                'success' => false,
                'error' => "this email doesn't exist",
            ]);
        }
        // future check if the email is already validate or not
        else if (true) { 
            Flight::json([
                'success' => true,
            ]);
        }
        else {
            Flight::json([
                'success' => false,
                'error' => "This email is already verified",
            ]);
        }
    }
}