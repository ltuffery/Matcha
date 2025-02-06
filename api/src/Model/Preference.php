<?php

namespace Matcha\Api\Model;

/**
 * @method Preference find(array $where)
 */
class Preference extends Model
{
    protected string $table = 'preferences';
    protected array $uniques = ['user_id'];

    public int $user_id = 0;
    public int|null $age_minimum;
    public int|null $age_maximum;
    public int $distance_maximum = 10;
    public bool $by_tags = true;
    public string $sexual_preferences = 'A';

    public function getIdColumn(): string
    {
        return 'user_id';
    }

    protected function getId(): int
    {
        return $this->user_id;
    }

}
