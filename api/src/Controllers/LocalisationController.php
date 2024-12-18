<?php

namespace Matcha\Api\Controllers;

use ChrisUllyott\IpApi;
use Flight;

class LocalisationController
{
    public function update(): void
    {
        $request = Flight::request();
        $user = Flight::user();

        if (isset($request->data->lat) && isset($request->data->lon)) {
            $user->lon = $request->data->lon;
            $user->lat = $request->data->lat;
        } else {
            $api = new IpApi();
            $ip = $request->ip;
            $loc = $api->get($ip);

            if ($loc->status == 'fail') {
                Flight::json([], 202);
                return;
            }

            $user->lon = $loc->lon;
            $user->lat = $loc->lat;
        }

        $user->save();

        Flight::json([], 204);
    }
}