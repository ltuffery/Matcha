<?php

namespace Matcha\Api\Controllers;

use Flight;
use Matcha\Api\Model\Message;
use Matcha\Api\Model\User;
use Matcha\Api\Validator\Validator;
use PDO;

class ChatController
{
    public function index(): void
    {
        $matches = Flight::user()->matches();

        Flight::json($matches);
    }

    public function show(string $username): void
    {
        $receiver = User::find([
            'username' => $username,
        ]);
        $stmt = Flight::db()->prepare("
            SELECT * 
            FROM messages 
            WHERE (sender_id = :sender_id AND receiver_id = :receiver_id)
            OR (sender_id = :receiver_id AND receiver_id = :sender_id)
            ORDER BY created_at;
        ");

        $stmt->execute([
            'sender_id' => Flight::user()->id,
            'receiver_id' => $receiver->id,
        ]);

        $messages = array_map(
            fn (array $data) => Message::morph($data)->getData(),
            $stmt->fetchAll(PDO::FETCH_ASSOC)
        );

        Flight::json($messages);
    }

    public function store(string $username): void
    {
        Validator::make([
            'content' => 'required',
        ]);

        $receiver = User::find([
            'username' => $username,
        ]);
        $message = new Message();

        $message->sender_id = Flight::user()->id;
        $message->receiver_id = $receiver->id;
        $message->content = htmlspecialchars(Flight::request()->data->content);

        $message->save();

        Flight::json([], 201);
    }
}