<?php

namespace Matcha\Api\Controllers;

use Flight;
use Matcha\Api\Controllers\Notifications\NotificationType;
use Matcha\Api\Model\Message;
use Matcha\Api\Model\User;
use Matcha\Api\Resources\MatchUserResource;
use Matcha\Api\Resources\MessageResource;
use Matcha\Api\Validator\Validator;
use PDO;

class ChatController
{
    public function index(): void
    {
        $matches = Flight::user()->matches();

        Flight::json(
            MatchUserResource::collection($matches)
        );
    }

    public function show(string $username): void
    {
        if (!Flight::user()->hasMatch($username)) {
            Flight::json([
                "message" => "Forbidden.",
            ], 403);
            return;
        }

        $receiver = User::find([
            'username' => $username,
        ]);

        $messages = Message::allOf(Flight::user(), $receiver);

        $this->updateMessageViews($messages);

        Flight::json([
            "avatar" => $receiver->getAvatar(),
            "username" => $username,
            "first_name" => $receiver->first_name,
            "last_name" => $receiver->last_name,
            "messages" => MessageResource::collection($messages),
        ]);
    }

    /**
     * @param Message[] $messages
     */
    private function updateMessageViews(array &$messages): void
    {
        $updates = [];

        foreach ($messages as $message) {
            if ($message->receiver_id == Flight::user()->id && !$message->view) {
                $updates[] = $message->id;
                $message->view = true;
            }
        }

        if (!empty($updates)) {
            $sql = "UPDATE messages SET view=1 WHERE id IN (" . implode(",", $updates) . ")";

            $stmt = Flight::db()->prepare($sql);
            $stmt->execute();
        }
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
        $user = Flight::user();

        $message->sender_id = $user->id;
        $message->receiver_id = $receiver->id;
        $message->content = htmlspecialchars(Flight::request()->data->content);

        $saved = $message->save();

        $user->notificationFor($receiver, NotificationType::MESSAGE, $user->first_name . ' sent you a message');

        Flight::json(new MessageResource($saved), 201);
    }

    public function delete(string $username, int $id): void
    {
        $message = Message::find([
            'id' => $id,
        ]);

        if (is_null($message)) {
            Flight::json([
                'message' => 'Not Found',
            ], 404);
            return;
        }

        if (Flight::user()->id != $message->sender_id) {
            Flight::json([
                'message' => 'Forbiden',
            ], 403);
            return;
        }

        $message->delete();

        Flight::json([], 203);
    }
}
