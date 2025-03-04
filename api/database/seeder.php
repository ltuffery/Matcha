<?php

use Matcha\Database\Seeders\DatabaseSeeder;

require_once dirname(__DIR__) . '/vendor/autoload.php';

$options = getopt("n:");
$databaseSeeder = new DatabaseSeeder();
$n = $options["n"] ?? 1;

$databaseSeeder->run(intval($n));