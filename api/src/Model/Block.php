<?php

namespace Matcha\Api\Model;

/**
 * @method Block find(array $where)
 */
class Block extends Model
{
    protected string $table = 'user_blocked';
    protected array $uniques = ['user_id', 'blocked_id'];

    public int $user_id;
    public int $blocked_id;
    public string $created_at;

}
