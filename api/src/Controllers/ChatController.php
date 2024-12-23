<?php

namespace Matcha\Api\Controllers;

use Flight;

class ChatController
{
    public function index(): void
    {
        $matches = Flight::user()->matches();

        Flight::json($matches);
    }

    public function show(string $username): void
    {
        // TODO: All message by sender and receiver
    }

    public function store(string $username): void
    {
        // TODO: store message
    }
}