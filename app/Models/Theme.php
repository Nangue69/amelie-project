<?php

declare(strict_types=1);

namespace App\Models;

use App\Core\Database;
use App\Core\Settings;

final class Theme
{
    public static function all(): array
    {
        return Database::all('SELECT * FROM `themes` ORDER BY `position`');
    }

    public static function activeKey(): string
    {
        $key = Settings::get('active_theme', 'borgogna');

        return preg_match('/^[a-z0-9_-]+$/', $key) === 1 ? $key : 'borgogna';
    }

    public static function activate(string $key): bool
    {
        $exists = Database::value('SELECT COUNT(*) FROM `themes` WHERE `tkey` = :k', ['k' => $key]);

        if ((int) $exists === 0) {
            return false;
        }

        Database::query('UPDATE `themes` SET `is_active` = 0');
        Database::update('themes', ['is_active' => 1], '`tkey` = :k', ['k' => $key]);
        Settings::put('active_theme', $key);

        return true;
    }

    /**
     * Los tres colores del tema, para pintar la muestra en el panel.
     */
    public static function swatchColors(array $theme): array
    {
        $colors = array_filter(array_map('trim', explode(',', (string) $theme['swatch'])));

        return array_values(array_filter(
            $colors,
            static fn (string $c): bool => preg_match('/^#[0-9A-Fa-f]{3,8}$/', $c) === 1
        ));
    }
}
