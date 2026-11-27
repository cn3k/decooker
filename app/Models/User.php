<?php

class User
{
    // false if the username is already taken
    public static function create(string $username, string $password): bool
    {
        $stmt = Database::get()->prepare('INSERT INTO users (username, password_hash) VALUES (?, ?)');
        try {
            $stmt->execute([$username, password_hash($password, PASSWORD_DEFAULT)]);
            return true;
        } catch (PDOException $e) {
            return false;
        }
    }

    // user row on success, null on bad credentials
    public static function login(string $username, string $password): ?array
    {
        $stmt = Database::get()->prepare('SELECT id, username, password_hash FROM users WHERE username = ?');
        $stmt->execute([$username]);
        $user = $stmt->fetch();

        if ($user && password_verify($password, $user['password_hash'])) {
            return $user;
        }
        return null;
    }
}
