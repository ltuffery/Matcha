<?php

namespace Matcha\Api\Controllers\Profile;

use Flight;
use Matcha\Api\Model\User;
use Matcha\Api\Resources\ProfileResource;

class ProfileSuggestionController
{
    public function index(): void
    {
        Flight::json(
            ProfileResource::collection(
                User::all()
            )
        );
    }
}