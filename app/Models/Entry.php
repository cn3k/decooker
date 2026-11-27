<?php
class Entry
{
    public static function allFor(int $userId): array
    {
        $stmt = Database::get()->prepare('SELECT * FROM entries WHERE user_id = ? ORDER BY id DESC');
        $stmt->execute([$userId]);
        return $stmt->fetchAll();
    }

    public static function create(int $userId, string $action, string $format, string $input, string $output): void
    {
        $stmt = Database::get()->prepare(
            'INSERT INTO entries (user_id, action, format, input, output) VALUES (?, ?, ?, ?, ?)'
        );
        $stmt->execute([$userId, $action, $format, $input, $output]);
    }

    public static function delete(int $id, int $userId): void
    {
        $stmt = Database::get()->prepare('DELETE FROM entries WHERE id = ? AND user_id = ?');
        $stmt->execute([$id, $userId]);
    }
}
