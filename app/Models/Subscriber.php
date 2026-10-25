<?php

declare(strict_types=1);

namespace App\Models;

use App\Core\Database;

final class Subscriber
{
    /**
     * Devuelve 'new', 'exists' o 'reactivated'.
     */
    public static function subscribe(string $email, string $lang, string $source = 'footer'): string
    {
        $email    = mb_strtolower(trim($email));
        $existing = Database::one('SELECT * FROM `subscribers` WHERE `email` = :e', ['e' => $email]);

        if ($existing !== null) {
            if ($existing['status'] === 'unsubscribed') {
                Database::update('subscribers', [
                    'status' => 'active',
                    'lang'   => $lang,
                ], '`id` = :id', ['id' => $existing['id']]);

                return 'reactivated';
            }

            return 'exists';
        }

        Database::insert('subscribers', [
            'email'        => $email,
            'lang'         => $lang,
            'status'       => 'active',
            'token'        => bin2hex(random_bytes(16)),
            'source'       => $source,
            'confirmed_at' => date('Y-m-d H:i:s'),
        ]);

        return 'new';
    }

    public static function all(string $status = ''): array
    {
        if ($status !== '') {
            return Database::all(
                'SELECT * FROM `subscribers` WHERE `status` = :s ORDER BY `created_at` DESC',
                ['s' => $status]
            );
        }

        return Database::all('SELECT * FROM `subscribers` ORDER BY `created_at` DESC');
    }

    public static function countActive(): int
    {
        return (int) Database::value('SELECT COUNT(*) FROM `subscribers` WHERE `status` = "active"', [], 0);
    }

    public static function unsubscribeByToken(string $token): bool
    {
        if ($token === '') {
            return false;
        }

        return Database::update(
            'subscribers',
            ['status' => 'unsubscribed'],
            '`token` = :t',
            ['t' => $token]
        ) > 0;
    }

    public static function delete(int $id): void
    {
        Database::delete('subscribers', '`id` = :id', ['id' => $id]);
    }

    public static function toCsv(array $rows): string
    {
        $out = "email;idioma;estado;origen;alta\n";

        foreach ($rows as $row) {
            $out .= implode(';', [
                $row['email'],
                $row['lang'],
                $row['status'],
                $row['source'],
                $row['created_at'],
            ]) . "\n";
        }

        return $out;
    }
}
