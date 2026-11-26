<?php

namespace Matcha\Api\Model;

use Matcha\Api\Validator\Asserts\NotBlank;

/**
 * @extends Model<Reaction>
 */
class Reaction extends Model
{
    protected string $table = 'reactions';

    public int $user_id;

    public int $message_id;

    #[NotBlank]
    public string $emoji;
}