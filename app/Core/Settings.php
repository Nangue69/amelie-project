<?php

declare(strict_types=1);

namespace App\Core;

final class Settings
{
    private static array $items = [];
    private static bool $loaded = false;

    private static function cacheFile(): string
    {
        return STORAGE_PATH . '/cache/settings.php';
    }

    private static function load(): void
    {
        if (self::$loaded) {
            return;
        }

        $cache = self::cacheFile();

        if (is_file($cache)) {
            $data = require $cache;

            if (is_array($data)) {
                self::$items  = $data;
                self::$loaded = true;

                return;
            }
        }

        self::refresh();
    }

    public static function refresh(): void
    {
        $rows = Database::all('SELECT `skey`, `svalue` FROM `settings`');
        $items = [];

        foreach ($rows as $row) {
            $items[$row['skey']] = $row['svalue'];
        }

        self::$items  = $items;
        self::$loaded = true;

        self::writeCache($items);
    }

    private static function writeCache(array $items): void
    {
        $dir = dirname(self::cacheFile());

        if (!is_dir($dir)) {
            @mkdir($dir, 0775, true);
        }

        @file_put_contents(
            self::cacheFile(),
            '<?php return ' . var_export($items, true) . ';',
            LOCK_EX
        );
    }

    public static function get(string $key, string $default = ''): string
    {
        self::load();

        $value = self::$items[$key] ?? null;

        return $value === null || $value === '' ? $default : (string) $value;
    }

    public static function bool(string $key, bool $default = false): bool
    {
        self::load();

        if (!array_key_exists($key, self::$items)) {
            return $default;
        }

        return in_array((string) self::$items[$key], ['1', 'true', 'on', 'yes'], true);
    }

    public static function float(string $key, float $default = 0.0): float
    {
        $value = self::get($key, '');

        return $value === '' ? $default : (float) $value;
    }

    public static function int(string $key, int $default = 0): int
    {
        $value = self::get($key, '');

        return $value === '' ? $default : (int) $value;
    }

    public static function all(): array
    {
        self::load();

        return self::$items;
    }

    public static function group(string $group): array
    {
        return Database::all(
            'SELECT `skey`, `svalue` FROM `settings` WHERE `sgroup` = :g ORDER BY `skey`',
            ['g' => $group]
        );
    }

    public static function put(string $key, string $value): void
    {
        $exists = Database::value('SELECT COUNT(*) FROM `settings` WHERE `skey` = :k', ['k' => $key]);

        if ((int) $exists > 0) {
            Database::update('settings', ['svalue' => $value], '`skey` = :k', ['k' => $key]);
        } else {
            Database::insert('settings', ['skey' => $key, 'svalue' => $value, 'sgroup' => 'general']);
        }

        self::refresh();
    }
}
