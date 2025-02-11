<?php

namespace Matcha\Api\Model;

class Notification extends Model
{
    protected string $table = 'notifications';

    public int $user_id;
    public int $sender_id;
    public string $type;
    public string $content;
    public bool $view = false;
    public string $created_at;

    public function sender(): User
    {
        return User::find([
            'id' => $this->sender_id,
        ]);
    }
}