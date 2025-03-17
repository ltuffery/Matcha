<?php

namespace Matcha\Api\Model;

use Matcha\Api\Validator\Asserts\Maximum;
use Matcha\Api\Validator\Asserts\Minimum;

/**
 * @method Preference find(array $where)
 */
class Preference extends Model
{
    protected string $table = 'preferences';
    protected array $uniques = ['user_id'];

    public int $user_id = 0;

    #[Minimum(18), Maximum(80)]
    public int $age_minimum;

    #[Minimum(18), Maximum(80)]
    public int $age_maximum;

    #[Minimum(1)]
    public int $distance_maximum = 10;

    public bool $by_tags = true;

    public string $sexual_preferences = 'A';

    public float $lat = 0;

    public float $lon = 0;

    public bool $is_custom_loc = false;

    public function user(): User
    {
        return User::find([
            'id' => $this->user_id,
        ]);
    }

}
