<?php

namespace Matcha\Api\Model;

/**
 * @method static User find()
 */
class User extends Model
{
    protected string $table = 'users';

    public int $id = 0;
    public string $username;
    public string $password;
    public string $email;
    public int $age;
    public string $first_name;
    public string $last_name;
    public string $gender;
    public string $sexual_preferences;
    public string $biography;
    public string $created_at;
    public int $email_verified;
    public string $temporary_email_token;

    public function jsonSerialize(): array
    {
        return [
//            'id' => $this->id,
            'username' => $this->username,
            'email' => $this->email,
//            'created_at' => $this->created_at,
        ];
    }
}
