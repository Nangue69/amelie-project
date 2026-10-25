<?php

declare(strict_types=1);

namespace App\Core;

final class Lang
{
    private static string $current = 'es';
    private static array $strings = [];
    private static ?array $available = null;

    /**
     * Idiomas activos, indexados por codigo.
     */
    public static function available(): array
    {
        if (self::$available !== null) {
            return self::$available;
        }

        $rows = Database::all(
            'SELECT `code`, `name`, `native_name`, `is_default`
               FROM `languages`
              WHERE `is_active` = 1
              ORDER BY `position`'
        );

        $languages = [];

        foreach ($rows as $row) {
            $languages[$row['code']] = $row;
        }

        if ($languages === []) {
            $languages = ['es' => ['code' => 'es', 'name' => 'Espanol', 'native_name' => 'Espanol', 'is_default' => 1]];
        }

        return self::$available = $languages;
    }

    public static function codes(): array
    {
        return array_keys(self::available());
    }

    public static function isValid(string $code): bool
    {
        return array_key_exists($code, self::available());
    }

    public static function default(): string
    {
        $configured = Settings::get('default_lang', 'es');

        if (self::isValid($configured)) {
            return $configured;
        }

        foreach (self::available() as $code => $language) {
            if ((int) $language['is_default'] === 1) {
                return $code;
            }
        }

        return (string) array_key_first(self::available());
    }

    public static function set(string $code): void
    {
        self::$current = self::isValid($code) ? $code : self::default();
        self::$strings = [];
    }

    public static function current(): string
    {
        return self::$current;
    }

    public static function nativeName(string $code): string
    {
        return self::available()[$code]['native_name'] ?? strtoupper($code);
    }

    /**
     * Detecta el idioma preferido del navegador entre los activos.
     */
    public static function detectFromBrowser(): string
    {
        $header = $_SERVER['HTTP_ACCEPT_LANGUAGE'] ?? '';

        foreach (explode(',', $header) as $chunk) {
            $code = strtolower(substr(trim(explode(';', $chunk)[0]), 0, 2));

            if ($code !== '' && self::isValid($code)) {
                return $code;
            }
        }

        return self::default();
    }

    private static function loadStrings(): void
    {
        if (self::$strings !== []) {
            return;
        }

        $rows = Database::all(
            'SELECT `skey`, `value` FROM `ui_strings` WHERE `lang` = :lang',
            ['lang' => self::$current]
        );

        $strings = [];

        foreach ($rows as $row) {
            $strings[$row['skey']] = $row['value'];
        }

        self::$strings = $strings;
    }

    /**
     * Traduce una clave. Los argumentos se sustituyen con sprintf.
     */
    public static function get(string $key, array $args = []): string
    {
        self::loadStrings();

        $value = self::$strings[$key] ?? $key;

        return $args === [] ? $value : vsprintf($value, $args);
    }

    public static function allStrings(string $code): array
    {
        return Database::all(
            'SELECT `id`, `skey`, `value` FROM `ui_strings` WHERE `lang` = :lang ORDER BY `skey`',
            ['lang' => $code]
        );
    }
}
