<?php

namespace Matcha\Api\Model;

use Matcha\Api\Validator\Asserts\NotBlank;

class Tag extends Model
{
    protected string $table = 'tags';
    protected array $uniques = ['name'];

    #[NotBlank()]
    public string $name;
}
