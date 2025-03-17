<?php

namespace Matcha\Api\Model;

use Matcha\Api\Validator\Asserts\NotBlank;

class Photo extends Model
{
    protected string $table = 'photos';
    protected array $uniques = ['name'];

    #[NotBlank()]
    public string $name;
    public int $user_id;

    public function user(): User
    {
        return User::find([
            'id' => $this->user_id,
        ]);
    }
}
