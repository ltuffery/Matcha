<?php

use Matcha\Api\controllers\AuthController;

Flight::route('GET /', function () {
    Flight::json([
        "version" => '0.1',
    ]);
});

Flight::group('/auth', function () {
    Flight::route('POST /register', [AuthController::class, 'register']);
});

// 404 route
Flight::map('notFound', function() {
    Flight::json([
        "message" => "Not found"
    ], 404);
});