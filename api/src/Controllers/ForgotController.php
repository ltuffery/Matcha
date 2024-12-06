<?php

namespace Matcha\Api\Controllers;

use Flight;
use InvalidDataException;
use Matcha\Api\Model\User;
use Matcha\Api\Validator\Validator;
use ReflectionException;
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

class ForgotController
{

    private function emailForgotSend($userTarget): void
    {
        $mail = new PHPMailer(true);
        
        try
        {
            //Server settings
            $mail->isSMTP();
            $mail->Host       = $_ENV['SMTP_HOST'];
            $mail->Username   = $_ENV['SMTP_USER'];
            $mail->Password   = $_ENV['SMTP_PASS'];
            $mail->SMTPAuth   = true;
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;
            $mail->Port       = 465;

            // Generate token/link and save it in db
            $temporaryToken = $userTarget->username . (string)rand();
            $temporaryToken = password_hash($temporaryToken, PASSWORD_DEFAULT);
            $url = "http://localhost:1212/forgot?user=" . $userTarget->username . "&token=" . $temporaryToken;
            $userTarget->temporary_email_token = $temporaryToken;
            $userTarget->save();
            // get and replace body mail
            $bodyMail = file_get_contents(__DIR__ . '/../template/forgot.html');
            // $bodyMail = str_replace('{{username}}', htmlspecialchars($userTarget->username, ENT_QUOTES, 'UTF-8'), $bodyMail);
            // $bodyMail = str_replace('{{url}}', $url, $bodyMail);
            $bodyMail = str_replace(
                ['{{username}}', '{{url}}'],
                [htmlspecialchars($userTarget->username, ENT_QUOTES, 'UTF-8'), $url],
                $bodyMail);

            //Recipients
            $mail->setFrom('noreply@matcha.com', 'Matcha');
            $mail->addAddress($userTarget->email, $userTarget->username);
    
            $mail->isHTML(true);
            $mail->Subject = 'Verify your email';
            $mail->Body    = $bodyMail;
            $mail->AltBody = 'This is your link to verify your mail : ' . $url;
    
            $mail->CharSet = 'UTF-8';
            $mail->Encoding = 'base64';
            $mail->send();
        }
        catch (Exception $e)
        {
            echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
        }
    }

    /**
     * @throws InvalidDataException
     * @throws ReflectionException
     */
    public function forgotCredencial(): void
    {
        Validator::make([
            'email' => 'required|email',
        ]);

        $request = Flight::request();

        $user = User::find([
            'email' => $request->data->email,
        ]);

        if ($user == null)
        {
            Flight::json([
                'success' => false,
                'error' => "this email doesn't exist",
            ], 404);
        }
        else if ($user->email_verified == true)
        {
            $this->emailForgotSend($user);
            Flight::json([
                'success' => true,
            ], 200);
        }
        else
        {
            Flight::json([
                'success' => false,
                'error' => "This email is not verified",
            ], 409); //change code ?
        }
    }

    /**
     * @throws InvalidDataException
     * @throws ReflectionException
     */
    public function tokenVerify(): void
    {
        Validator::make([
            'username' => 'required',
            // add required token
        ]);

        $request = Flight::request();

        $user = User::find([
            'username' => $request->data->username,
        ]);

        if ($user == null)
        {
            Flight::json([
                'success' => false,
                'error' => "bad user",
            ], 404);
        }
        else if ($user->temporary_email_token == $request->data->token && $request->data->token != "")
        {
            $changePwdToken = (string)rand();
            $user->temporary_email_token = $changePwdToken;
            $user->save();
            Flight::json([
                'success' => true,
                'token' => $changePwdToken,
            ], 200); //good code ?
        }
        else
        {
            Flight::json([
                'success' => false,
                'error' => "Bad token, you don't have right",
            ], 403); //change error code?
        }
    }

    /**
     * @throws InvalidDataException
     * @throws ReflectionException
     */
    public function changePwd(): void
    {
        Validator::make([
            'username' => 'required',
            'pass1' => 'required',
            'pass2' => 'required',
            // add required token
        ]);

        $request = Flight::request();

        $user = User::find([
            'username' => $request->data->username,
        ]);

        if ($user == null)
        {
            Flight::json([
                'success' => false,
                'error' => "bad user",
            ], 404);
        }
        else if ($request->data->pass1 != $request->data->pass2)
        {
            Flight::json([
                'success' => false,
                'error' => "2 password are not same",
            ], 403); //good code ?
        }

        else if ($user->temporary_email_token == $request->data->token && $request->data->token != "")
        {
            $user->temporary_email_token = "";
            $user->password = password_hash($request->data->pass1, PASSWORD_DEFAULT);
            $user->save();
            Flight::json([
                'success' => true,
            ], 200); //good code ?
        }

        else
        {
            Flight::json([
                'success' => false,
                'error' => "Bad token, you don't have right",
            ], 403); //change error code?
        }
    }
}