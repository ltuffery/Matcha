<?php

namespace Matcha\Api\Controllers\Profile;

use Flight;
use Matcha\Api\Model\User;
use Matcha\Api\Resources\SearchableUserResource;

class SearchProfileController
{

    public function index(): void
    {
        /** @var User $me */
        $me = Flight::user();
        $data = Flight::request()->query;
        $where = [];

        if (isset($data->age_gap)) {
            $age_gap = explode('-', $data->age_gap);

            $where[] = [
                'birthday',
                '<=',
                date('Y-m-d', strtotime('-' . $age_gap[0] . ' year')),
            ];
            $where[] = [
                'birthday',
                '>=',
                date('Y-m-d', strtotime('-' . $age_gap[1] . ' year')),
            ];
        }

        if (isset($data->fame_gap)) {
            // Use JOIN in `preferences`
        }

        $users = User::where($where);
        $users = array_filter($users, fn ($user) => !$me->isBlocking($user));

        Flight::json(
            SearchableUserResource::collection($users)
        );
    }
}
