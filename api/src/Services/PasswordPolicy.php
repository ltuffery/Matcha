<?php

namespace Matcha\Api\Services;

class PasswordPolicy
{
    private const WORDLISTS = [
        __DIR__ . '/../../wordlists/english.txt',
        __DIR__ . '/../../wordlists/french.txt',
        __DIR__ . '/../../wordlists/common-passwords.txt',
    ];

    private static ?array $words = null;

    private static function load(): array
    {
        if (self::$words === null) {
            self::$words = [];
            foreach (self::WORDLISTS as $file) {
                if (!is_readable($file)) {
                    error_log("PasswordPolicy: wordlist missing: $file");
                    continue;
                }
                foreach (file($file, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES) as $w) {
                    self::$words[$w] = true;
                }
            }
        }
        return self::$words;
    }

    /** @return string|null message d'erreur, ou null si le mot de passe est accepté */
    public static function validate(string $password, array $context = []): ?string
    {
        if (mb_strlen($password) < 8) {
            return 'Password must be at least 8 characters';
        }

        $core = preg_replace('/^[^a-z]+|[^a-z]+$/', '', $password);
        $words = self::load();

        if (isset($words[$password]) || ($core !== '' && isset($words[$core]))) {
            return 'This password is too common (dictionary word)';
        }

        foreach ($context as $value) {
            $v = (string) $value;
            if (mb_strlen($v) >= 3 && str_contains($password, $v)) {
                return 'Password must not contain your personal information';
            }
        }

        return null;
    }
}
