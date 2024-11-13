<?php

use Matcha\Api\controllers\AuthController;

Flight::route('GET /', [AuthController::class, 'register']);

// 404 route
Flight::map('notFound', function() {
    Flight::json([
        "message" => "Not found"
    ], 404);
});