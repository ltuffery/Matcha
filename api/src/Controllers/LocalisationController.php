<?php

namespace Matcha\Api\Controllers;

use ChrisUllyott\IpApi;
use Flight;

class LocalisationController
{
    public function update(): void
    {
        $request = Flight::request();
        $userPreferences = Flight::user()->getPreferences();

        if (isset($request->data->lat) && isset($request->data->lon)) {
            $userPreferences->lon = $request->data->lon;
            $userPreferences->lat = $request->data->lat;
        }

        $userPreferences->save();

        Flight::json([
            'message' => 'Location updated',
        ]);
    }
}
