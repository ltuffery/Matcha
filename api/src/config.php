<?php

Flight::register('db', PDO::class, [
    'mysql:host=mysql;dbname=' . $_ENV['MYSQL_DATABASE'],
    $_ENV['MYSQL_USER'],
    $_ENV['MYSQL_PASSWORD'],
]);

Flight::before('start', function (array $params) {
    $request = Flight::request();
    $response = Flight::response();

    $response->header("Access-Control-Allow-Origin", $request->getVar('HTTP_ORIGIN'));
    $response->header('Access-Control-Allow-Credentials', 'true');
    $response->header('Access-Control-Max-Age', '86400');

    if ($request->method === 'OPTIONS') {
        if ($request->getVar('HTTP_ACCESS_CONTROL_REQUEST_METHOD') !== '') {
            $response->header(
                'Access-Control-Allow-Methods', 'GET, POST, PUT, DELETE, PATCH, OPTIONS, HEAD'
            );
        }
        if ($request->getVar('HTTP_ACCESS_CONTROL_REQUEST_HEADERS') !== '') {
            $response->header(
                "Access-Control-Allow-Headers",
                $request->getVar('HTTP_ACCESS_CONTROL_REQUEST_HEADERS')
            );
        }

        $response->status(200);
        $response->send();
        exit;
    }
});
