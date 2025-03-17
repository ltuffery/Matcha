<?php

namespace Matcha\Api\Model;

use Matcha\Api\Validator\Asserts\NotBlank;

/**
 * @method static Notification find(array $where)
 */
class Notification extends Model
{
    protected string $table = 'notifications';

    public int $user_id;

    public int $sender_id;

    public string $type;

    #[NotBlank()]
    public string $content;

    public bool $view = false;

    #[NotBlank()]
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
