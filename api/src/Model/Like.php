<?php

namespace Matcha\Api\Model;

class Like extends Model
{
    public int $user_id;
    public int $liked_id;
}