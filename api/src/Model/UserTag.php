<?php

namespace Matcha\Api\Model;

class UserTag extends Model
{
    protected string $table = 'user_tags';
    protected array $uniques = ['user_id', 'tag_id'];

    public int $user_id;
    public int $tag_id;
}
