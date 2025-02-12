<?php

namespace Matcha\Api\Services;

use Exception;
use PHPMailer\PHPMailer\PHPMailer;

class Mailer
{
    public PHPMailer $mailer;

    public function __construct()
    {
        $this->mailer = new PHPMailer(true);

        $this->mailer->isSMTP();
        $this->mailer->SMTPDebug = 2;
        $this->mailer->Host = getenv('SMTP_HOST');
        $this->mailer->Username = getenv('SMTP_USER');
        $this->mailer->Password = getenv('SMTP_PASS');
        $this->mailer->SMTPAuth = true;
        $this->mailer->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;
        $this->mailer->Port = 465;

        $this->mailer->setFrom('noreply@matcha.com', 'Matcha');
    }

    /**
     * @throws Exception
     */
    public static function to(string $address, string $name): self
    {
        $mail = new Mailer();

        $mail->mailer->addAddress($address, $name);

        return $mail;
    }

    /**
     * @throws Exception
     */
    public function setFrom(string $address, string $name = null): self
    {
        $this->mailer->setFrom($address, $name);
        return $this;
    }

    public function send(string $subject, string $body): bool
    {
        $this->build($subject, $body);

        return $this->mailer->send();
    }

    private function build(string $subject, string $body): void
    {
        $this->mailer->isHTML(true);
        $this->mailer->Subject = $subject;
        $this->mailer->Body = $body;
        $this->mailer->AltBody = htmlspecialchars($body, ENT_QUOTES);
        $this->mailer->CharSet = 'UTF-8';
        $this->mailer->Encoding = 'base64';
    }
}
