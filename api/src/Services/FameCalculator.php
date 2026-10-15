<?php

namespace Matcha\Api\Services;

use Exception;
use Matcha\Api\Model\User;
use Flight;

class FameCalculator
{
    private const K_LIKE = 12;
    private const K_PASS = 8;
    private const K_VIEW = 2;

    private const MIN_FAME = 0;
    private const MAX_FAME = 2500;

    public function __construct(
        private User $actor,   // liker
        private User $target   // liked
    ) {
    }

    public function onLike(): void
    {
        $this->save(self::K_LIKE * (1 - $this->expected()));
    }

    public function onPass(): void
    {
        $this->save(-self::K_PASS * $this->expected());
    }

    public function onView(): void
    {
        $this->save(self::K_VIEW * (1 - $this->expected()));
    }

    private function expected(): float
    {
        $diff = $this->actor->fame_rating - $this->target->fame_rating;

        return 1 / (1 + 10 ** ($diff / 400));
    }

    private function save(float $delta): void
    {
        $stmt = Flight::db()->prepare(
            'UPDATE users
                SET fame_rating = LEAST(:max, GREATEST(:min, fame_rating + :delta))
              WHERE id = :id'
        );
        $stmt->execute([
            'max'   => self::MAX_FAME,
            'min'   => self::MIN_FAME,
            'delta' => $delta,
            'id'    => $this->target->id,
        ]);
    }
}
