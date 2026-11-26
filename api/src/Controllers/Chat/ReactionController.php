<?php

namespace Matcha\Api\Controllers\Chat;

use Flight;
use Matcha\Api\Model\Message;
use Matcha\Api\Model\Reaction;
use Matcha\Api\Model\User;
use Matcha\Api\Resources\ReactionResource;
use Matcha\Api\Validator\Validator;

class ReactionController
{
    public function store(string $username, string $messageId): void
    {
        Validator::required([
            'emoji',
        ]);

        /** @var User $user */
        $user = Flight::user();
        $message = Message::find(['id' => (int) $messageId]);
        $emoji = Flight::request()->data->emoji;

        if (is_null($message)) {
            Flight::json([
                'message' => 'Message not found',
                'success' => false,
            ], 404);
            return;
        }

        if ($message->sender_id == $user->id || $message->receiver_id == $user->id) {
            $reaction = $message->addReaction($user->id, $emoji);

            Flight::redis()->publish('app_events', json_encode([
                'type' => 'create_reaction',
                'target_username' => $username,
                'payload' => [
                    'username' => $user->username,
                    'message_id' => $messageId,
                    'emoji' => $reaction->emoji,
                ],
            ]));

            Flight::json(new ReactionResource($reaction));
        } else {
            Flight::json([
                'success' => false,
                'message' => 'Sorry, you are not allowed to reac this message',
            ], 403);
        }
    }

    public function destroy(string $username, string $messageId): void
    {
        /** @var User $user */
        $user = Flight::user();
        /** @var Reaction|null $reaction */
        $reaction = Reaction::find([
            'message_id' => $messageId,
            'user_id' => $user->id,
        ]);

        if (is_null($reaction)) {
            Flight::json([
                'success' => false,
                'message' => 'Reaction not found',
            ], 404);
            return;
        }

        Flight::redis()->publish('app_events', json_encode([
            'type' => 'delete_reaction',
            'target_username' => $username,
            'payload' => [
                'username' => $user->username,
                'message_id' => $messageId,
                'emoji' => $reaction->emoji,
            ],
        ]));

        $reaction->delete();
        Flight::json(new ReactionResource($reaction));
    }
}