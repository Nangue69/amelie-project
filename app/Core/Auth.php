<?php

declare(strict_types=1);

namespace App\Core;

final class Auth
{
    private const KEY = '_admin_user';
    private const MAX_ATTEMPTS = 5;
    private const LOCK_MINUTES = 15;

    public static function attempt(string $email, string $password): bool
    {
        $user = Database::one(
            'SELECT * FROM `users` WHERE `email` = :email LIMIT 1',
            ['email' => $email]
        );

        if ($user === null || (int) $user['is_active'] !== 1) {
            return false;
        }

        if (self::isLocked($user)) {
            return false;
        }

        if (!password_verify($password, $user['password'])) {
            self::registerFailure($user);

            return false;
        }

        Database::update('users', [
            'failed_attempts' => 0,
            'locked_until'    => null,
            'last_login_at'   => date('Y-m-d H:i:s'),
        ], '`id` = :id', ['id' => $user['id']]);

        Session::regenerate();
        Session::set(self::KEY, [
            'id'    => (int) $user['id'],
            'name'  => $user['name'],
            'email' => $user['email'],
            'role'  => $user['role'],
        ]);

        return true;
    }

    private static function isLocked(array $user): bool
    {
        if ($user['locked_until'] === null) {
            return false;
        }

        return strtotime((string) $user['locked_until']) > time();
    }

    public static function lockedUntil(string $email): ?string
    {
        $value = Database::value(
            'SELECT `locked_until` FROM `users` WHERE `email` = :email',
            ['email' => $email]
        );

        if (!is_string($value) || strtotime($value) <= time()) {
            return null;
        }

        return $value;
    }

    private static function registerFailure(array $user): void
    {
        $attempts = (int) $user['failed_attempts'] + 1;
        $data     = ['failed_attempts' => $attempts];

        if ($attempts >= self::MAX_ATTEMPTS) {
            $data['failed_attempts'] = 0;
            $data['locked_until']    = date('Y-m-d H:i:s', time() + self::LOCK_MINUTES * 60);
        }

        Database::update('users', $data, '`id` = :id', ['id' => $user['id']]);
    }

    public static function check(): bool
    {
        return Session::has(self::KEY);
    }

    public static function user(): ?array
    {
        $user = Session::get(self::KEY);

        return is_array($user) ? $user : null;
    }

    public static function id(): int
    {
        return (int) (self::user()['id'] ?? 0);
    }

    public static function isAdmin(): bool
    {
        return (self::user()['role'] ?? '') === 'admin';
    }

    public static function logout(): void
    {
        Session::forget(self::KEY);
        Session::regenerate();
    }

    public static function log(string $action, string $entity = '', ?int $entityId = null, string $description = ''): void
    {
        $user = self::user();

        Database::insert('activity_log', [
            'user_id'     => $user['id'] ?? null,
            'user_name'   => $user['name'] ?? 'Sistema',
            'action'      => $action,
            'entity'      => $entity,
            'entity_id'   => $entityId,
            'description' => mb_substr($description, 0, 255),
        ]);
    }
}
