<?php

namespace Matcha\Api\Factory;

class UserFactory extends Factory
{
    protected function define(): array
    {
        return [
            'username' => faker()->userName,
            'email' => faker()->email,
            'password' => password_hash('password', PASSWORD_DEFAULT),
            'email_verified' => true,
            'first_name' => faker()->firstName(),
            'last_name' => faker()->lastName(),
            'birthday' => faker()->dateTimeBetween('-50 years', '-18 years'),
            'gender' => array_rand(['M', 'F', 'O']) + 1,
            'sexual_preferences' => array_rand(['M', 'F', 'O', 'A']) + 1,
            'biography' => faker()->sentence(),
        ];
    }
}
