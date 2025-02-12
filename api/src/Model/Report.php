<?php

namespace Matcha\Api\Model;

class Report extends Model
{
    protected string $table = 'user_reports';
    protected array $uniques = ['user_id', 'reported_id'];

    public int $user_id;
    public int $reported_id;
    public string $raison;
    public string $created_at;
}