<?php

namespace Matcha\Api\Controllers;

use Flight;
use InvalidDataException;
use Matcha\Api\Model\User;
use Matcha\Api\Validator\Validator;
use ReflectionException;
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

function emailVerifSend($userTarget): void
{
    $mail = new PHPMailer(true);
    
    try {
        
        //Server settings
        // $mail->SMTPDebug = SMTP::DEBUG_SERVER;                      //Enable verbose debug output
        $mail->isSMTP();                                            //Send using SMTP
        $mail->Host       = 'mail.delaware.o2switch.net';                     //Set the SMTP server to send through
        $mail->SMTPAuth   = true;                                   //Enable SMTP authentication
        $mail->Username   = 'matcha@swotex.fr';                     //SMTP username
        $mail->Password   = 'matcha_password';                               //SMTP password
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;            //Enable implicit TLS encryption
        $mail->Port       = 465; 

        //Recipients
        $mail->setFrom('noreply@matcha.com', 'Matcha');
        // $mail->setFrom('bouffemoilecul@denis.fr');
        // $mail->addAddress('swotex21@gmail.com', 'swotex');
        $mail->addAddress($userTarget->email, $userTarget->username);

        $mail->isHTML(true);
        $mail->Subject = 'Verify your email';
        $bodyMail = file_get_contents(__DIR__ . '/file.html');
        $bodyMail = str_replace('{{username}}', htmlspecialchars($userTarget->username, ENT_QUOTES, 'UTF-8'), $bodyMail);
        $request = Flight::request();
        $temporaryToken = $userTarget->username . (string)rand();
        $temporaryToken = password_hash($temporaryToken, PASSWORD_DEFAULT);
        $url = "http://localhost:1212/verify?user=" . $userTarget->username . "&token=" . $temporaryToken;
        $bodyMail = str_replace('{{url}}', $url, $bodyMail);
        $mail->Body    = $bodyMail;

        $mail->AltBody = 'This is the body in plain text for non-HTML mail clients';

        $mail->CharSet = 'UTF-8';
        $mail->Encoding = 'base64';
        $mail->send();
        echo 'Message has been sent';
    } catch (Exception $e) {
        echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
    }
}

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
            emailVerifSend($user);
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

        if ($user == null) {
            Flight::json([
                'success' => false,
                'error' => "Bad Link",
            ]);
        }

        // add [if] to know if the user is already verifyed 

        // future check if the token is ok
        else if (true) {
            // future update db
            // echo $request->data->token;
            Flight::json([
                'success' => true,
            ]);
        }
        else {
            Flight::json([
                'success' => false,
                'error' => "Bad Link",
            ]);
        }
    }
}