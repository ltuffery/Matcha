<?php

namespace Matcha\Database\Seeders;

use Matcha\Api\Model\Message;
use Matcha\Api\Model\User;

class MessageSeeder implements SeederInterface
{
    public function run(): void
    {
        $users = User::all();

        foreach ($users as $user) {
            $matches = $user->matches();

            $elements = faker()->randomElements($matches, rand(0, count($matches)));

            foreach ($elements as $el) {
                $isMe = rand(0, 1);
                $sender = $user->id;
                $receiver = $el->id;

                if (!$isMe) {
                    $sender = $el->id;
                    $receiver = $user->id;
                }

                for ($i = 0; $i < rand(0, 50); $i++) {
                    $message = new Message();

                    $message->sender_id = $sender;
                    $message->receiver_id = $receiver;
                    $message->content = faker()->sentence(15);
                    $message->view = true;;

                    $message->save();
                }
            }
        }
    }
}