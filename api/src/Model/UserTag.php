<?php

namespace Matcha\Api\Model;

use Flight;

class UserTag extends Model
{
    protected string $table = 'user_tags';
    protected array $uniques = ['user_id', 'tag_id'];

    public int $user_id;
    public int $tag_id;

    public static function deleteAllFromUser(User $user)
    {
        Flight::db()->query("DELETE FROM `user_tags` WHERE `user_id` = " . $user->id);
    }
}
