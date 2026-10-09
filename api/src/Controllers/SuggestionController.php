<?php

namespace Matcha\Api\Controllers;

use Flight;
use Matcha\Api\Model\User;

class SuggestionController
{
    private const SORTS = ['distance', 'fame', 'common_tags', 'age'];
    private const MAX_LIMIT = 50;

    public function index()
    {
        $query = Flight::request()->query;

        $sort   = $query['sort'] ?? null;
        $order  = strtolower($query['order'] ?? 'desc');
        $offset = (int) ($query['offset'] ?? 0);
        $limit  = (int) ($query['limit'] ?? 20);

        if ($sort !== null && !in_array($sort, self::SORTS, true)) {
            Flight::json(['message' => 'Invalid sort'], 422);
            return;
        }

        if (!in_array($order, ['asc', 'desc'], true)) {
            Flight::json(['message' => 'Invalid order'], 422);
            return;
        }

        if ($offset < 0 || $limit < 1 || $limit > self::MAX_LIMIT) {
            Flight::json(['message' => 'Invalid pagination'], 422);
            return;
        }

        $result = Flight::user()->suggestions($sort, $order, $offset, $limit);

        Flight::json([
            'count'    => count($result['profiles']),
            'has_more' => $result['has_more'],
            'profiles' => $result['profiles'],
        ]);
    }
}
