<?php

Flight::register('db', PDO::class, [
    'mysql:host=mysql;dbname=' . $_ENV['MYSQL_DATABASE'],
    $_ENV['MYSQL_USER'],
    $_ENV['MYSQL_PASSWORD'],
]);