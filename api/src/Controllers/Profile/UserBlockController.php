<?php

namespace Matcha\Api\Controllers\Profile;

use Flight;
use Matcha\Api\Model\Block;
use Matcha\Api\Model\User;

class UserBlockController
{
    public function index(): void
    {
        // TODO: Use resource (security!!!)
        Flight::json(
            array_map(
                function ($block) {
                    $user = User::find(['id' => $block->blocked_id]);
                    return [
                        'username' => $user->username,
                        'avatar'   => $user->getPhotosUrl()[0], // adapte au nom réel
                    ];
                },
                Block::all(['user_id' => Flight::user()->id])
            )
        );
    }

    public function store(string $username): void
    {
        $block = new Block();

        $block->user_id = Flight::user()->id;
        $blocked = User::find(['username' => $username]);

        if (is_null($blocked)) {
            Flight::json([
                'message' => 'User not found',
            ], 404);
            return;
        }

        $block->blocked_id = $blocked->id;

        $block->save();

        Flight::json(['success' => true], 201);
    }

    public function destroy(string $username): void
    {
        $blocked = User::find(['username' => $username]);
        $block = Block::find(['blocked_id' => $blocked->id]);

        if (!$block) {
            Flight::json([
                'message' => 'This user is not blocked',
            ], 404);
            return;
        }

        if ($block->user_id != Flight::user()->id) {
            Flight::json([
                'message' => 'Forbidden',
            ], 403);
            return;
        }

        $block->delete();
        Flight::json([], 203);
    }
}
