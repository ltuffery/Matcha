<?php

namespace Matcha\Api\Model;

/**
 * @method static User find(array $data)
 * @method static User morph(array $object)
 * @method static User[] all()
 * @method User save()
 * @method User create()
 * @method User update()
 */
class User extends Model
{
    protected string $table = 'users';

    public string $username;
    public string $password;
    public string $email;
    public int|null $age;
    public string|null $first_name;
    public string|null $last_name;
    public string|null $gender;
    public string|null $sexual_preferences;
    public string|null $biography;
    public string $created_at;
    public bool $email_verified;
    public string|null $temporary_email_token;
    public bool $online;

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
