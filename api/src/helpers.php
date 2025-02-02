<?php

use Bluemmb\Faker\PicsumPhotosProvider;
use Faker\Factory;
use Faker\Generator;

define("BASE_PATH", dirname(__DIR__));

if (!function_exists('faker')) {
    function faker(): Generator
    {
        $factory = Factory::create();

        $factory->addProvider(new PicsumPhotosProvider($factory));

        return $factory;
    }
}

if (!function_exists('template')) {
    function template(string $file): string
    {
        return dirname(__DIR__) . '/template/' . trim($file, '/');
    }
}

if (!function_exists('haversine')) {
    function haversine($lat1, $lon1, $lat2, $lon2): float|int
    {
        $a = pow(sin(($lat2 - $lat1) / 2), 2) + cos($lat1) * cos($lat2) * pow(sin(($lon2 - $lon1) / 2), 2);
        $c = 2 * atan2(sqrt($a), sqrt(1 - $a));

        return 6371 * $c; // 6371 is earth radius
    }
}
