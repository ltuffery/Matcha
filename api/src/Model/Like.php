<?php

namespace Matcha\Api\Model;

class Like extends Model
{
    protected string $table = 'likes';
    protected array $uniques = ['user_id', 'liked_id'];

    public int $user_id;
    public int $liked_id;
    public string $created_at;
}
