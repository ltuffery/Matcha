<?php

namespace Matcha\Api\Services;

use Matcha\Api\Services\Mailer;
use Matcha\Api\Model\User;


class MailSender
{
    public Mailer $mailer;
    public User $userTarget;
    public string $object;
    public string $body;

    public function __construct(User $userTarget)
    {
        $this->mailer = new Mailer();
        $this->userTarget = $userTarget;
    }

    public function mailVerify()
    {
        $token = bin2hex(random_bytes(32));
        $url = "http://" . getenv("APP_HOST") . ":8011/verify?user="
            . urlencode($this->userTarget->username) . "&token=" . $token;

        $this->userTarget->temporary_email_token = $token;
        $this->userTarget->save();

        $this->object = 'Verify your email';

        $this->body = file_get_contents(template('verify-email.html'));
        $this->body = str_replace(
            ['{{username}}', '{{url}}'],
            [htmlspecialchars($this->userTarget->username, ENT_QUOTES, 'UTF-8'), $url],
            $this->body
        );
        return $this;
    }

    public function mailPasswordReset()
    {
        $token = $this->userTarget->username . (string)rand();
        $token = password_hash($token, PASSWORD_DEFAULT);
        $url = "http://" . getenv("APP_HOST") . ":8011/forgot?user=" . $this->userTarget->username . "&token=" . $token;

        $this->userTarget->temporary_email_token = $token;
        $this->userTarget->save();

        $this->object = 'Forgot credencial';

        $this->body = file_get_contents(template('forgot.html'));
        $this->body = str_replace(
            ['{{username}}', '{{url}}'],
            [htmlspecialchars($this->userTarget->username, ENT_QUOTES, 'UTF-8'), $url],
            $this->body
        );
        return $this;
    }

    public function send()
    {
        Mailer::to($this->userTarget->email, $this->userTarget->username)->send($this->object, $this->body);
    }
}
