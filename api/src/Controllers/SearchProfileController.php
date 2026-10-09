<?php

namespace Matcha\Api\Controllers;

use Flight;
use Matcha\Api\Model\User;
use Matcha\Api\Resources\ProfileResource;
use Matcha\Api\Resources\SearchableUserResource;

class SearchProfileController
{
    public const NUMBER_USERS_SEARCHABLE = 5;

    private const SORTS = [
        'year'         => 'age ASC',
        'fame_rating'  => 'u.fame_rating DESC',
        'localisation' => 'distance ASC',
        'common_tags'  => 'common_tags DESC',
    ];

    public function index(): void
    {
        $query = Flight::request()->query;

        /** @var User $me */
        $me = Flight::user();

        $ageRanges  = $this->parseRanges($query->years ?? null);
        $fameRanges = $this->parseRanges($query->fame_rating ?? null);
        $tags       = $this->parseList($query->tags ?? null);
        $sorts      = $this->parseList($query->sort ?? null);
        $maxKm      = isset($query->distance) ? (float) $query->distance : null;

        $table  = User::getTable();
        $params = [];

        $select = "
    SELECT u.*,
        p.lat,
        p.lon,
        TIMESTAMPDIFF(YEAR, u.birthday, CURDATE()) AS age,
        ST_Distance_Sphere(
            POINT(p.lon, p.lat),
            POINT(mp.lon, mp.lat)
        ) / 1000 AS distance,
        (
            SELECT COUNT(*)
            FROM user_tags ut
            JOIN user_tags mt ON mt.tag_id = ut.tag_id AND mt.user_id = ?
            WHERE ut.user_id = u.id
        ) AS common_tags
    FROM {$table} u
    LEFT JOIN preferences p  ON p.user_id = u.id
    LEFT JOIN preferences mp ON mp.user_id = ?
";
        array_push($params, $me->id, $me->id);

        $where = [
            'u.id != ?',
            'NOT EXISTS (SELECT 1 FROM user_blocked b WHERE b.user_id = ? AND b.blocked_id = u.id)',
        ];
        array_push($params, $me->id, $me->id);

        if (!empty($query->q)) {
            $where[]  = 'u.username LIKE ?';
            $params[] = '%' . $query->q . '%';
        }

        if ($fameRanges) {
            $where[] = $this->rangesSql('u.fame_rating', $fameRanges, $params);
        }

        if ($tags) {
            $placeholders = implode(',', array_fill(0, count($tags), '?'));
            $where[] = "EXISTS (
            SELECT 1 FROM user_tags ut
            JOIN tags t ON t.id = ut.tag_id
            WHERE ut.user_id = u.id AND t.name IN ($placeholders)
        )";
            array_push($params, ...$tags);
        }

        $having = [];

        if ($ageRanges) {
            $having[] = $this->rangesSql('age', $ageRanges, $params);
        }

        if ($maxKm !== null) {
            $having[] = 'distance <= ?';
            $params[] = $maxKm;
        }

        $order = array_map(fn ($s) => self::SORTS[$s], array_filter($sorts, fn ($s) => isset(self::SORTS[$s])));
        $order[] = 'u.id ASC';

        $sql = $select
            . ' WHERE ' . implode(' AND ', $where)
            . ($having ? ' HAVING ' . implode(' AND ', $having) : '')
            . ' ORDER BY ' . implode(', ', $order)
            . ' LIMIT ' . self::NUMBER_USERS_SEARCHABLE;

        $users = User::raw($sql, $params);

        Flight::json(ProfileResource::collection($users));
    }

    /** [[18,25],[30,40]] → "(col BETWEEN ? AND ? OR col BETWEEN ? AND ?)" */
    private function rangesSql(string $column, array $ranges, array &$params): string
    {
        $parts = [];
        foreach ($ranges as [$min, $max]) {
            $parts[] = "$column BETWEEN ? AND ?";
            array_push($params, $min, $max);
        }
        return '(' . implode(' OR ', $parts) . ')';
    }

    private function parseRanges(?string $raw): array
    {
        if (!$raw) return [];

        if (!str_contains($raw, '-')) {
            $parts = array_map('intval', explode(',', $raw));
            return count($parts) === 2 ? [$parts] : [];
        }

        $ranges = [];
        foreach (explode(',', $raw) as $r) {
            $p = explode('-', $r);
            if (count($p) === 2 && is_numeric($p[0]) && is_numeric($p[1])) {
                $ranges[] = [(int) $p[0], (int) $p[1]];
            }
        }
        return $ranges;
    }

    private function parseList(?string $raw): array
    {
        return $raw ? array_values(array_filter(array_map('trim', explode(',', $raw)))) : [];
    }
}
