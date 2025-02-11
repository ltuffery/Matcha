<?php

namespace Matcha\Api\Controllers\Notifications;

class NotificationType
{
    public const MESSAGE = 'MESSAGE';
    public const LIKE = 'LIKE';
    public const UNLIKE = 'UNLIKE';
    public const MATCH = 'MATCH';
    public const VIEW = 'VIEW';
    public const ALL = [self::MESSAGE, self::LIKE, self::UNLIKE, self::MATCH, self::VIEW];

    public static function valid(string $type): string
    {
        return in_array(strtoupper($type), self::ALL);
    }
}