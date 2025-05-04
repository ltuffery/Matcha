<?php

namespace Matcha\Api\Model;

use Matcha\Api\Validator\Asserts\NotBlank;

class Report extends Model
{
    protected string $table = 'user_reports';
    protected array $uniques = ['user_id', 'reported_id'];

    public int $user_id;

    public int $reported_id;

    #[NotBlank()]
    public string $raison;

    #[NotBlank()]
    public string $created_at;
}
