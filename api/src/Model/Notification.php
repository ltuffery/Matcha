<?php

namespace Matcha\Api\Model;

class Notification extends Model
{
    protected string $table = 'notifications';

    public int $user_id;
    public string $type;
    public string $content;
    public bool $view = false;
    public string $created_at;
}