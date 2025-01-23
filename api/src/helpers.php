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
