<?php

namespace Matcha\Api\Model;

use Firebase\JWT\JWT;

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

    public function generateJWT(): string
    {
        $time = time();

        return JWT::encode([
            'username' => $this->username,
            'exp' => $time + 600,
            'iat' => $time,
        ], getenv('SECRET_KEY'), 'HS256');
    }

    /**
     * Create a like from the user to the user passed as a parameter
     *
     * @param User $user
     * @return void
     */
    public function like(User $user): void
    {
        $like = new Like();

        $like->user_id = $this->id;
        $like->liked_id = $user->id;

        $like->save();
    }

    public function unlike(User $user): void
    {
        $like = Like::find([
            'user_id' => $this->id,
            'liked_id' => $user->id,
        ]);

        if (!is_null($like)) {
            $like->delete();
        }
    }

    /**
     * Get all the likes that the user has made
     *
     * @return Like[]
     */
    public function likes(): array
    {
        return Like::all([
            'user_id' => $this->id,
        ]);
    }

    public static function authenticate(string $username, string $password): User|false
    {
        $user = User::find([
            'username' => $username,
        ]);

        if (!is_null($user) && password_verify($password, $user->password)) {
            return $user;
        }

        return false;
    }
}
