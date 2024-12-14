<?php

use Faker\Factory;
use Faker\Generator;

if (!function_exists('faker')) {
    function faker(): Generator
    {
        return Factory::create();
    }
}

if (!function_exists('template')) {
    function template(string $file): string
    {
        return dirname(__DIR__) . '/template/' . trim($file, '/');
    }
}
