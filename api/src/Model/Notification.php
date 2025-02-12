<?php

namespace Matcha\Api\Model;

/**
 * @method static Notification find(array $where)
 */
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

    public static function to(User $from, User $to, string $type, string $content): Notification
    {
        $notification = new Notification();

        $notification->user_id = $to->id;
        $notification->sender_id = $from->id;
        $notification->type = $type;
        $notification->content = $content;

        return $notification->save();
    }
}
