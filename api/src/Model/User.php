<?php

namespace Matcha\Api\Model;

class User extends Model
{
    protected string $table = 'users';

    public int $id;
    public string $username;
    public string $password;
    public string $email;
    public int $created_at;

    public function jsonSerialize(): array
    {
        return [
            'id' => $this->id,
            'username' => $this->username,
            'email' => $this->email,
            'created_at' => $this->created_at,
        ];
    }
}