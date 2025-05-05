<?php

namespace Matcha\Api\Model;

use Flight;
use Matcha\Api\Validator\Asserts\NotBlank;
use PDO;
use PDOStatement;
use ReflectionException;

/**
 * @method static Message find(array $where)
 */
class Message extends Model
{
    protected string $table = 'messages';

    public int $sender_id;

    public int $receiver_id;

    #[NotBlank()]
    public string $content;

    public bool $view = false;

    #[NotBlank()]
    public string $created_at;

    /**
     * Get last message of user1 and user2
     * @throws ReflectionException
     */
    public static function lastOf(User $user1, User $user2): ?Model
    {
        $message = self::allOf($user1, $user2, 1);

        if (is_null($message)) {
            return null;
        }

        return $message[0];
    }

    /**
     * @param User $user1
     * @param User $user2
     * @param int $limit
     * @return Message[]
     * @throws ReflectionException
     */
    public static function allOf(User $user1, User $user2, int $limit = 50): array|null
    {
        $messages = Message::where([
            ['sender_id', '=', $user1->id],
            ['receiver_id', '=', $user2->id],
        ])
        ->orWhere('sender_id', '=', $user2->id)
        ->andWhere('receiver_id', '=', $user1->id)
        ->orderBy('created_at', 'DESC')
        ->limit($limit)
        ->get(true);

        return $messages;
    }

    /**
     * @param User $me
     * @param User $other
     * @param int $limit
     * @return int
     * @throws ReflectionException
     */
    public static function countUnreadOf(User $me, User $other, int $limit = 50): int
    {
        $messages = Message::where([
                ['sender_id', '=', $other->id],
                ['receiver_id', '=', $me->id],
                ['view', '=', 0]
            ])
            ->orderBy('created_at', 'DESC')
            ->limit($limit)
            ->get(true);

        if (is_null($messages)) {
            return 0;
        }

        return count($messages);
    }
}
