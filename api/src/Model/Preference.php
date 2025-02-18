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
    public float|null $lat = 0;
    public float|null $lon = 0;
    public bool $is_custom_loc = false;

    public function user(): User
    {
        return User::find([
            'id' => $this->user_id,
        ]);
    }

}
