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
        } else {
            $api = new IpApi();
            $ip = $request->ip;
            $loc = $api->get($ip);

            if ($loc->status == 'fail') {
                Flight::json([], 202);
                return;
            }

            $userPreferences->lon = $loc->lon;
            $userPreferences->lat = $loc->lat;
        }

        $userPreferences->save();

        Flight::json([], 204);
    }
}
