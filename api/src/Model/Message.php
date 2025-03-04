<?php

namespace Matcha\Api\Model;

use Flight;
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
    public string $content;
    public bool $view = false;
    public string $created_at;

    /**
     * Get last message of user1 and user2
     * @throws ReflectionException
     */
    public static function lastOf(User $user1, User $user2): ?Model
    {
        /** @var PDOStatement $stmt */
        $stmt = Flight::db()->prepare("
            SELECT * 
            FROM messages 
            WHERE (sender_id = :sender_id AND receiver_id = :receiver_id)
            OR (sender_id = :receiver_id AND receiver_id = :sender_id)
            ORDER BY created_at DESC
            LIMIT 1;
        ");

        $stmt->execute([
            'sender_id' => $user1->id,
            'receiver_id' => $user2->id,
        ]);

        $data = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$data) {
            return null;
        }

        return Message::morph($data);
    }

    /**
     * @param User $user1
     * @param User $user2
     * @param int $limit
     * @return Message[]
     * @throws ReflectionException
     */
    public static function allOf(User $user1, User $user2, int $limit = 50): array
    {
        $stmt = Flight::db()->prepare("
            SELECT * 
            FROM messages 
            WHERE (sender_id = :sender_id AND receiver_id = :receiver_id)
            OR (sender_id = :receiver_id AND receiver_id = :sender_id)
            ORDER BY created_at DESC
            LIMIT " . $limit . ";");

        $stmt->execute([
            'sender_id' => $user1->id,
            'receiver_id' => $user2->id,
        ]);

        return array_map(
            fn (array $data) => Message::morph($data),
            $stmt->fetchAll(PDO::FETCH_ASSOC)
        );
    }

    /**
     * @param User $me
     * @param User $other
     * @param int $limit
     * @return int
     * @throws ReflectionException
     */
    public static function allUnreadOf(User $me, User $other, int $limit = 50): int
    {
        $stmt = Flight::db()->prepare("
            SELECT * 
            FROM messages 
            WHERE sender_id = :sender_id AND receiver_id = :receiver_id AND view = 0
            ORDER BY created_at
            LIMIT " . $limit . ";");

        $stmt->execute([
            'sender_id' => $other->id,
            'receiver_id' => $me->id,
        ]);

        return count($stmt->fetchAll(PDO::FETCH_ASSOC));
    }
}
