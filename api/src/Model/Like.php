<?php

namespace Matcha\Api\Model;

/**
 * @method static Like find(array $where)
 */
class Like extends Model
{
    protected string $table = 'likes';
    protected array $uniques = ['user_id', 'liked_id'];

    public int $user_id;
    public int $liked_id;
    public string $created_at;

    public function liked(): User
    {
        return User::find(['id' => $this->liked_id]);
    }

    public function user(): User
    {
        return User::find(['id' => $this->user_id]);
    }
}
