<?php

namespace Matcha\Api\Model;

/**
 * @method static Pass find(array $where)
 */
class Pass extends Model
{
    protected string $table = 'passes';
    protected array $uniques = ['user_id', 'target_id'];

    public int $user_id;
    public int $target_id;
    public string $created_at;

    public function liked(): User
    {
        return User::find(['id' => $this->$target_id]);
    }

    public function user(): User
    {
        return User::find(['id' => $this->user_id]);
    }
}
