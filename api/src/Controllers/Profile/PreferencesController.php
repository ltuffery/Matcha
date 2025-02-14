<?php

namespace Matcha\Api\Controllers\Profile;

use Flight;
use Matcha\Api\Model\Preference;
use Matcha\Api\Resources\PreferencesResource;

class PreferencesController
{
    public function index(): void
    {
        Flight::json(
            new PreferencesResource(
                Flight::user()->getPreferences()
            )
        );
    }

    public function update(): void
    {
        /** @var Preference $preferences */
        $preferences = Flight::user()->getPreferences();

        foreach (Flight::request()->data as $name => $value) {
            $preferences->{$name} = $value;
        }



        $preferences->save();
    }
}
