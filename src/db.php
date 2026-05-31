<?php

declare(strict_types=1);

require_once __DIR__ . '/config.php';

function db(): PDO
{
    static $pdo = null;

    if ($pdo instanceof PDO) {
        return $pdo;
    }

    $dsn = sprintf('mysql:host=%s;dbname=%s;charset=utf8mb4', DB_HOST, DB_NAME);

    $pdo = new PDO($dsn, DB_USER, DB_PASS, [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    ]);

    ensureSchemaCompatibility($pdo);

    return $pdo;
}

function tableHasColumn(string $table, string $column): bool
{
    static $cache = [];
    $key = $table . '.' . $column;

    if (array_key_exists($key, $cache)) {
        return $cache[$key];
    }

    $stmt = db()->prepare(
        'SELECT COUNT(*) FROM information_schema.COLUMNS WHERE TABLE_SCHEMA = :schema AND TABLE_NAME = :table AND COLUMN_NAME = :column'
    );
    $stmt->execute([
        'schema' => DB_NAME,
        'table' => $table,
        'column' => $column,
    ]);

    $cache[$key] = ((int) $stmt->fetchColumn()) > 0;

    return $cache[$key];
}

function ensureSchemaCompatibility(PDO $pdo): void
{
    try {
        if (!tableHasColumn('users', 'avatar_path')) {
            $pdo->exec('ALTER TABLE users ADD COLUMN avatar_path VARCHAR(255) DEFAULT NULL AFTER phone');
        }

        if (!tableHasColumn('users', 'is_banned')) {
            $pdo->exec('ALTER TABLE users ADD COLUMN is_banned TINYINT(1) NOT NULL DEFAULT 0 AFTER role');
        }

        if (!tableHasColumn('reviews', 'user_id')) {
            $pdo->exec('ALTER TABLE reviews ADD COLUMN user_id INT NULL AFTER id');
        }

        if (!tableHasColumn('reviews', 'updated_at')) {
            $pdo->exec('ALTER TABLE reviews ADD COLUMN updated_at TIMESTAMP NULL DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP AFTER created_at');
        }
    } catch (Throwable) {
        // Совместимость со старыми БД без прав ALTER.
    }
}
