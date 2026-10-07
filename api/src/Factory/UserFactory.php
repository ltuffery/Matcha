<?php

namespace Matcha\Api\Factory;

class UserFactory extends Factory
{
    protected function define(): array
    {
        $fakeUsername = faker()->userName;
        $fakeUsername = $fakeUsername. str_repeat('a', max(0, 5 - strlen($fakeUsername)));

        return [
            'username' => $fakeUsername,
            'email' => faker()->email,
            'password' => password_hash('password', PASSWORD_DEFAULT),
            'email_verified' => true,
            'first_name' => faker()->firstName(),
            'last_name' => faker()->lastName(),
            'birthday' => faker()->dateTimeBetween('-50 years', '-18 years')->format('Y-m-d'),
            'gender' => faker()->randomElement(['M', 'F', 'O']),
            'biography' => faker()->sentence(),
        ];
    }
}
