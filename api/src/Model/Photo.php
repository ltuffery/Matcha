<?php

namespace Matcha\Api\Model;

class Photo extends Model
{
    protected string $table = 'photos';
    protected array $uniques = ['name'];

    public string $name;
    public int $user_id;

    public function user(): User
    {
        return User::find([
            'id' => $this->user_id,
        ]);
    }
}