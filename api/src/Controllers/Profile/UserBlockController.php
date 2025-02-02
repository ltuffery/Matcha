<?php

namespace Matcha\Api\Controllers\Profile;

use Flight;
use Matcha\Api\Model\Block;
use Matcha\Api\Model\User;

class UserBlockController
{
    public function index(): void
    {
        Flight::json(
            array_map(
                fn ($id) => User::find(['id' => $id]),
                Block::all(['user_id' => Flight::user()->id])
            )
        );
    }

    public function store(string $username): void
    {
        $block = new Block();

        $block->user_id = Flight::user()->id;
        $block->blocked_id = User::find(['username' => $username])->id;

        $block->save();

        Flight::json(['success' => true], 201);
    }

    public function destroy(string $username): void
    {
        $block = Block::find(['blocked_id' => $username]);

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
