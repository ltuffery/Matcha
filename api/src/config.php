<?php

use Firebase\JWT\JWT;
use Firebase\JWT\Key;
use Matcha\Api\Exceptions\InvalidDataException;
use Matcha\Api\Exceptions\UniqueConstraintException;
use Matcha\Api\Model\User;

$_SERVER['SCRIPT_NAME'] = 'index.php';

Flight::register('db', PDO::class, [
    'mysql:host=mysql;dbname=' . getenv('MYSQL_DATABASE'),
    getenv('MYSQL_USER'),
    getenv('MYSQL_PASSWORD'),
]);

Flight::map('user', function () {
    $token = Flight::request()->header('Authorization');

    if (!$token) {
        return null;
    }

    $token = str_replace('Bearer ', '', $token);

    $playload = JWT::decode($token, new Key(getenv('SECRET_KEY'), 'HS256'));

    $user = User::find([
        'username' => $playload->username,
    ]);

    return $user;
});

Flight::map('error', function (Throwable $error) {
    $response = Flight::response();

    foreach ($response->headers() as $header => $value) {
        if (is_string($value)) {
            try {
                header($header . ": " . $value);
            } catch (Exception) {
            }
        }
    }

    if ($error instanceof InvalidDataException) {
        try {
            header("Content-Type: application/json; charset=utf-8", response_code: 400);
        } catch (Exception) {
        }

        $data = json_decode($response->getBody());

        echo json_encode([
            'message' => $data->message,
            'code' => $data->code,
        ]);
    } elseif ($error instanceof UniqueConstraintException) {
        header("Content-Type: application/json; charset=utf-8", response_code: 400);

        echo json_encode([
            'message' => $error->getMessage(),
        ]);
    } else {
        header("Content-Type: application/json; charset=utf-8", response_code: 500);

        echo json_encode([
            'message' => $error->getMessage(),
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
