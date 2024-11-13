<?php

use Matcha\Api\utils\CorsUtils;

Flight::register('db', PDO::class, [
    'mysql:host=mysql;dbname=' . $_ENV['MYSQL_DATABASE'],
    $_ENV['MYSQL_USER'],
    $_ENV['MYSQL_PASSWORD'],
]);

//$CorsUtil = new CorsUtils();

//Flight::before('start', $CorsUtil::class . "::set");