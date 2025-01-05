<?php

namespace Matcha\Api\Model;

use Flight;
use PDO;

class Message extends Model
{
    protected string $table = 'messages';

    public int $sender_id;
    public int $receiver_id;
    public string $content;
    public string $created_at;

    /**
     * Get last message of user1 and user2
     */
    public static function lastOf(User $user1, User $user2): Message
    {
        $stmt = Flight::db()->prepare("
            SELECT * 
            FROM messages 
            WHERE (sender_id = :sender_id AND receiver_id = :receiver_id)
            OR (sender_id = :receiver_id AND receiver_id = :sender_id)
            ORDER BY created_at DESC
            LIMIT 1;
        ");

        $stmt->execute([
            'sender_id' => $user1,
            'receiver_id' => $user2
        ]);

        return Message::morph(
            $stmt->fetch(PDO::FETCH_ASSOC)
        );
    }
}