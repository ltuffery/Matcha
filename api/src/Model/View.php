<?php

namespace Matcha\Api\Model;

class View extends Model
{
    protected string $table = 'profile_viewed';
    protected array $uniques = ['user_id', 'viewed_id'];

    public int $user_id;
    public int $viewed_id;
    public string $created_at;

    public function viewed(): User
    {
        return User::find(['id' => $this->viewed_id]);
    }

    public function user(): User
    {
        return User::find(['id' => $this->user_id]);
    }
}
