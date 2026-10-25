<?php

declare(strict_types=1);

namespace App\Models;

use App\Core\Database;

final class Message
{
    public static function store(array $data): int
    {
        return Database::insert('messages', $data);
    }

    public static function all(bool $onlyUnread = false): array
    {
        $where = $onlyUnread ? 'WHERE `is_read` = 0' : '';

        return Database::all("SELECT * FROM `messages` {$where} ORDER BY `created_at` DESC LIMIT 200");
    }

    public static function find(int $id): ?array
    {
        return Database::one('SELECT * FROM `messages` WHERE `id` = :id', ['id' => $id]);
    }

    public static function markRead(int $id): void
    {
        Database::update('messages', ['is_read' => 1], '`id` = :id', ['id' => $id]);
    }

    public static function delete(int $id): void
    {
        Database::delete('messages', '`id` = :id', ['id' => $id]);
    }

    public static function countUnread(): int
    {
        return (int) Database::value('SELECT COUNT(*) FROM `messages` WHERE `is_read` = 0', [], 0);
    }
}
