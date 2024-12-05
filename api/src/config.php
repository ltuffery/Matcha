<?php

include "Exceptions/InvalidDataException.php";

Flight::register('db', PDO::class, [
    'mysql:host=mysql;dbname=' . $_ENV['MYSQL_DATABASE'],
    $_ENV['MYSQL_USER'],
    $_ENV['MYSQL_PASSWORD'],
]);

Flight::map('error', function (Throwable $error) {
    $response = Flight::response();

    foreach ($response->headers() as $header => $value) {
        if (is_string($value)) {
            header($header . ": " . $value);
        }
    }

    if ($error instanceof InvalidDataException) {
        header("Content-Type: application/json; charset=utf-8", response_code: 400);

        $data = json_decode($response->getBody());

        echo json_encode([
            'message' => $data->message,
            'code' => $data->code,
        ]);
    } else {
        header("Content-Type: application/json; charset=utf-8", response_code: 500);

        echo json_encode([
            'message' => $error->getTraceAsString(),
        ]);
    }
});

Flight::before('start', function (array $params) {
    $request = Flight::request();
    $response = Flight::response();

    $response->header("Access-Control-Allow-Origin", '*');
    $response->header('Access-Control-Allow-Credentials', 'true');
    $response->header('Access-Control-Allow-Methods', 'GET, POST, PUT, DELETE, PATCH, OPTIONS, HEAD');
    $response->header('Access-Control-Allow-Headers', 'Content-Type, Authorization, X-Requested-With');
    $response->header('Access-Control-Max-Age', '86400');

    if ($request->method === 'OPTIONS') {
        $response->status(200);
        $response->send();
        exit;
    }
});
