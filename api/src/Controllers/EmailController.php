<?php

namespace Matcha\Api\Controllers;

use Flight;
use Matcha\Api\Exceptions\InvalidDataException;
use Matcha\Api\Model\User;
use Matcha\Api\Services\MailSender;
use Matcha\Api\Validator\Validator;
use ReflectionException;

class EmailController
{
    /**
     * @throws ReflectionException
     */
    public function emailVerify(): void
    {
        Validator::required([
            'email',
        ]);

        $request = Flight::request();

        $user = User::find([
            'email' => $request->data->email,
        ]);

        if ($user == null) {
            Flight::json([
                'success' => false,
                'error' => "this email doesn't exist",
            ], 404);
        } elseif (!$user->email_verified) {
            $mailer = new MailSender($user);
            $mailer->mailVerify()->send();

            Flight::json([
                'success' => true,
            ]);
        } else {
            Flight::json([
                'success' => false,
                'error' => "This email is already verified",
            ], 409);
        }
    }

    /**
     * @throws InvalidDataException
     * @throws ReflectionException
     */
    public function verifyToken(): void
    {
        Validator::required([
            'username',
            'token',
        ]);

        $request = Flight::request();

        $user = User::find([
            'username' => $request->data->username,
        ]);

        if ($user == null) {
            Flight::json([
                'success' => false,
                'error' => "Bad Link (user not found)",
            ], 404);
        } elseif (!$user->email_verified && $user->temporary_email_token == $request->data->token) {
            $user->temporary_email_token = "";
            $user->email_verified = true;
            $user->save();
            Flight::json([
                'success' => true,
            ], 201);
        } else {
            Flight::json([
                'success' => false,
                'error' => "Bad Link (Token broken or already verify)",
            ], 400);
        }
    }
}
