<?php

namespace Matcha\Api\Controllers;

use Flight;
use InvalidDataException;
use Matcha\Api\Model\User;
use Matcha\Api\Validator\Validator;
use ReflectionException;
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

class EmailController
{

    private function emailVerifSend($userTarget): void
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
            $url = "http://localhost:1212/verify?user=" . $userTarget->username . "&token=" . $temporaryToken;
            $userTarget->temporary_email_token = $temporaryToken;
            $userTarget->save();
            // get and replace body mail
            $bodyMail = file_get_contents(__DIR__ . '/../template/file.html');
            $bodyMail = str_replace('{{username}}', htmlspecialchars($userTarget->username, ENT_QUOTES, 'UTF-8'), $bodyMail);
            $bodyMail = str_replace('{{url}}', $url, $bodyMail);

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
    public function emailVerif(): void
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
        else if ($user->email_verified == false)
        {
            EmailController::emailVerifSend($user);
            Flight::json([
                'success' => true,
            ], 200);
        }
        else
        {
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
    public function verifToken(): void
    {
        Validator::make([
            'username' => 'required',
        ]);

        $request = Flight::request();

        $user = User::find([
            'username' => $request->data->username,
        ]);

        if ($user == null)
        {
            Flight::json([
                'success' => false,
                'error' => "Bad Link (user not found)",
            ], 404);
        }

        else if ($user->email_verified == false && $user->temporary_email_token == $request->data->token)
        {
            $user->temporary_email_token = "";
            $user->email_verified = true;
            $user->save();
            Flight::json([
                'success' => true,
            ], 201);
        }
        else
        {
            Flight::json([
                'success' => false,
                'error' => "Bad Link (Token broken or already verfy)",
            ], 400);
        }
    }
}