<?php

namespace Matcha\Api\Model;

class Like extends Model
{
    protected string $table = 'likes';

    public int $user_id;
    public int $liked_id;
}
