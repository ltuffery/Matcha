<?php

namespace Matcha\Api\Testing\Cases;

use Flight;
use PDO;

trait DatabaseTestCase
{
    public function setUpDatabase(): void
    {
        $migrationsFolder = dirname(__DIR__, 3) . '/database/migrations/';

        Flight::register('db', PDO::class, ['sqlite::memory:']);
        $this->loadAllMigrations($migrationsFolder);
    }

    private function loadAllMigrations(string $migrationsFolder): void
    {
        $files = preg_grep('/^([^.])/', scandir($migrationsFolder));
        
        foreach ($files as $file) {
            $sql = file_get_contents($migrationsFolder . $file, true);
            $sql = $this->parseSQL($sql);

            Flight::db()->exec($sql);
        }
    }

    private function parseSQL(string $sql): string
    {
        $sql = preg_replace(
            ['/AUTO_INCREMENT/', '/ENUM\([a-zA-Z0-9, \']+\)/i'],
            ['', 'TEXT'],
            $sql
        );
        $sql = $this->fixAlterTable($sql);

        return $sql;
    }

    private function fixAlterTable(string $sql): string
    {
        if (!str_starts_with($sql, "ALTER")) {
            return $sql;
        }

        $lines = explode("\n", $sql);
        $alterSqlLine = $lines[0];
        $lines = array_slice($lines, 1);

        foreach ($lines as $key => $value) {
            if (!empty($value)) {
                $lines[$key] = $alterSqlLine . ' ' . str_replace("ADD", "ADD COLUMN", $value);
            }
        }

        $sql = implode("\n", $lines);
        
        return str_replace(',', ';', $sql);
    }
}