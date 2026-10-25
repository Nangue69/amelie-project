<?php

declare(strict_types=1);

namespace App\Models;

use App\Core\Database;

final class User
{
    public static function all(): array
    {
        return Database::all(
            'SELECT `id`, `name`, `email`, `role`, `is_active`, `last_login_at`, `created_at`
               FROM `users` ORDER BY `name`'
        );
    }

    public static function find(int $id): ?array
    {
        return Database::one('SELECT * FROM `users` WHERE `id` = :id', ['id' => $id]);
    }

    public static function emailExists(string $email, int $exceptId = 0): bool
    {
        return (int) Database::value(
            'SELECT COUNT(*) FROM `users` WHERE `email` = :e AND `id` <> :id',
            ['e' => $email, 'id' => $exceptId],
            0
        ) > 0;
    }

    public static function create(string $name, string $email, string $password, string $role): int
    {
        return Database::insert('users', [
            'name'      => $name,
            'email'     => mb_strtolower($email),
            'password'  => password_hash($password, PASSWORD_BCRYPT),
            'role'      => $role === 'admin' ? 'admin' : 'editor',
            'is_active' => 1,
        ]);
    }

    public static function update(int $id, array $data): void
    {
        Database::update('users', $data, '`id` = :id', ['id' => $id]);
    }

    public static function setPassword(int $id, string $password): void
    {
        Database::update(
            'users',
            ['password' => password_hash($password, PASSWORD_BCRYPT)],
            '`id` = :id',
            ['id' => $id]
        );
    }

    public static function delete(int $id): void
    {
        Database::delete('users', '`id` = :id', ['id' => $id]);
    }

    public static function countAdmins(): int
    {
        return (int) Database::value(
            'SELECT COUNT(*) FROM `users` WHERE `role` = "admin" AND `is_active` = 1',
            [],
            0
        );
    }

    public static function activityLog(int $limit = 50): array
    {
        $limit = max(1, min($limit, 200));

        return Database::all('SELECT * FROM `activity_log` ORDER BY `created_at` DESC LIMIT ' . $limit);
    }
}
