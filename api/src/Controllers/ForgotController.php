<?php

namespace Matcha\Api\Controllers;

use Flight;
use Matcha\Api\Exceptions\InvalidDataException;
use Matcha\Api\Model\User;
use Matcha\Api\Services\MailSender;
use Matcha\Api\Validator\Validator;
use ReflectionException;

class ForgotController
{
    /**
     * @throws ReflectionException
     */
    public function forgotCredencial(): void
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
        } elseif ($user->email_verified) {
            $mailer = new MailSender($user);
            $mailer->mailPasswordReset()->send();

            Flight::json([
                'success' => true,
            ], 200);
        } else {
            Flight::json([
                'success' => false,
                'error' => "This email is not verified",
            ], 400);
        }
    }

    /**
     * @throws InvalidDataException
     * @throws ReflectionException
     */
    public function tokenVerify(): void
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
                'error' => "bad user",
            ], 404);
        } elseif ($user->temporary_email_token == $request->data->token && $request->data->token != "") {
            $changePwdToken = (string)rand();
            $user->temporary_email_token = $changePwdToken;
            $user->save();
            Flight::json([
                'success' => true,
                'token' => $changePwdToken,
            ], 200);
        } else {
            Flight::json([
                'success' => false,
                'error' => "Bad token, you don't have right",
            ], 403);
        }
    }

    /**
     * @throws InvalidDataException
     * @throws ReflectionException
     */
    public function changePwd(): void
    {
        Validator::required([
            'username',
            'newPassword',
            'confirmPassword',
            'token',
        ]);

        $request = Flight::request();

        $user = User::find([
            'username' => $request->data->username,
        ]);

        if ($user == null) {
            Flight::json([
                'success' => false,
                'error' => "bad user",
            ], 404);
        } elseif ($request->data->newPassword != $request->data->confirmPassword) {
            Flight::json([
                'success' => false,
                'error' => "2 password are not same",
            ], 400);
        } elseif ($user->temporary_email_token == $request->data->token && $request->data->token != "") {
            $user->temporary_email_token = "";
            $user->password = password_hash($request->data->newPassword, PASSWORD_DEFAULT);
            $user->save();
            Flight::json([
                'success' => true,
            ]);
        } else {
            Flight::json([
                'success' => false,
                'error' => "Bad token, you don't have right",
            ], 403);
        }
    }
}
