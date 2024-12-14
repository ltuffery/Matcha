<?php

namespace Matcha\Api\Managers;

use Firebase\JWT\JWT;
use Matcha\Api\Model\User;

class UserManager
{
    private static User|null $user = null;

    public function __construct(?User $user = null)
    {
        if (!is_null($this::$user) && is_null($user)) {
            return;
        }

        $this::$user = $user;
    }

    public function generateJWT(): string
    {
        $time = time();

        return JWT::encode([
            'username' => $this::$user->username,
            'exp' => $time + 600,
            'iat' => $time,
        ], getenv('SECRET_KEY'), 'HS256');
    }

    public function authenticate(string $username, string $password): bool
    {
        $user = User::find([
            'username' => $username,
        ]);

        if (!is_null($user) && password_verify($password, $user->password)) {
            $this::$user = $user;

            return true;
        }

        return false;
    }

    public function isAuthenticated(): bool
    {
        return !is_null($this::$user);
    }

    public function model(): User|null
    {
        return $this::$user;
    }
}