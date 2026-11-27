<?php
// sqlite - created automatically on first run

class Database
{
    private static ?PDO $pdo = null;

    public static function get(): PDO
    {
        if (self::$pdo === null) {
            $dir = __DIR__ . '/../../storage';
            if (!is_dir($dir)) {
                mkdir($dir, 0700, true);
            }

            self::$pdo = new PDO('sqlite:' . $dir . '/app.sqlite');
            self::$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            self::$pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
            self::$pdo->exec('PRAGMA foreign_keys = ON');

            self::$pdo->exec('CREATE TABLE IF NOT EXISTS users (
                id            INTEGER PRIMARY KEY AUTOINCREMENT,
                username      TEXT NOT NULL UNIQUE,
                password_hash TEXT NOT NULL
            )');

            self::$pdo->exec('CREATE TABLE IF NOT EXISTS entries (
                id         INTEGER PRIMARY KEY AUTOINCREMENT,
                user_id    INTEGER NOT NULL REFERENCES users(id) ON DELETE CASCADE,
                action     TEXT NOT NULL,
                format     TEXT NOT NULL,
                input      TEXT NOT NULL,
                output     TEXT NOT NULL,
                created_at TEXT NOT NULL DEFAULT CURRENT_TIMESTAMP
            )');
        }

        return self::$pdo;
    }
}
