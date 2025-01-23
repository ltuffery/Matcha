<?php

namespace Matcha\Api\Model;

class Tag extends Model
{
    protected string $table = 'tags';
    protected array $uniques = ['name'];

    public string $name;
}
