<?php

use Matcha\Api\Controllers\AuthController;
use Matcha\Api\Controllers\RegisterController;

Flight::route('GET /', function () {
    $content = file_get_contents(dirname(__DIR__) . '/composer.json');
    $data = json_decode($content, true);

    Flight::json([
        "version" => $data["version"],
        "authors" => $data["authors"],
    ]);
});

Flight::group('/auth', function () {
    Flight::route('POST /register', [RegisterController::class, 'store']);
    Flight::route('POST /login', [AuthController::class, 'login']);

});

// 404 route
Flight::map('notFound', function() {
    Flight::json([
        "message" => "Not found"
    ], 404);
});