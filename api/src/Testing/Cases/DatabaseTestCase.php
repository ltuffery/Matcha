<?php

namespace Matcha\Api\Testing\Cases;

use Flight;
use PDO;

trait DatabaseTestCase
{
    public function setUp(): void
    {
        Flight::register('db', PDO::class, [
            'mysql:host=mysql;dbname=' . getenv('MYSQL_DATABASE'),
            getenv('MYSQL_USER'),
            getenv('MYSQL_PASSWORD'),
        ]);
    }
}