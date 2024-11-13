<?php

namespace Matcha\Api\controllers;

use Flight;

class AuthController
{
    public function register() {
        Flight::json(["test" => Flight::request()->method]);
    }
}