<?php

$db = new PDO(
    'mysql:host=mysql;',
    getenv('MYSQL_USER'),
    getenv('MYSQL_PASSWORD'),
);

$db->exec("CREATE DATABASE IF NOT EXISTS " . getenv('MYSQL_DATABASE') . ";");

$db = new PDO(
    'mysql:host=mysql;dbname=' . getenv('MYSQL_DATABASE'),
    getenv('MYSQL_USER'),
    getenv('MYSQL_PASSWORD'),
);

function migration_table_exist(PDO $db): bool {
    $statement = $db->query("SHOW TABLES LIKE 'migrations'");

    if (is_bool($statement)) {
        return false;
    }

    return $statement->rowCount() > 0;
}

function save_migration(PDO $db, $migrationName): void {
    if (!migration_table_exist($db)) {
        return;
    }

    $statement = $db->prepare("INSERT INTO migrations (migration) VALUES (:migration_name)");

    $statement->bindValue(':migration_name', $migrationName);
    $statement->execute();
}

function already_exists(PDO $db, string $migrationName): bool
{
    if (!migration_table_exist($db)) {
        return false;
    }

    $statement = $db->prepare("SELECT * FROM migrations WHERE migration = :migration_name");
    $statement->bindValue(':migration_name', $migrationName);
    $statement->execute();

    return $statement->rowCount() > 0;
}

$files = preg_grep('/^([^.])/', scandir(__DIR__ . '/migrations'));

foreach ($files as $file) {
    $sql = file_get_contents(__DIR__ . '/migrations/' . $file, true);

    if (!already_exists($db, $file)) {
        $db->exec($sql);

        save_migration($db, $file);

        echo "Migrating " . $file . "\n";
    }
}

echo "Migrating all migrations\n";