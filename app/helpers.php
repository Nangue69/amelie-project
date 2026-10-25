<?php

declare(strict_types=1);

use App\Core\Csrf;
use App\Core\Lang;
use App\Core\Settings;

/**
 * Escapa texto para imprimirlo en HTML.
 */
function e(?string $value): string
{
    return htmlspecialchars((string) $value, ENT_QUOTES, 'UTF-8');
}

/**
 * Subdirectorio en el que vive la instalacion ('' si esta en la raiz).
 */
function base_path(): string
{
    static $base = null;

    if ($base !== null) {
        return $base;
    }

    $script = str_replace('\\', '/', dirname((string) ($_SERVER['SCRIPT_NAME'] ?? '')));

    return $base = ($script === '/' || $script === '.') ? '' : rtrim($script, '/');
}

/**
 * URL absoluta dentro de la instalacion, sin prefijo de idioma.
 */
function url(string $path = '/'): string
{
    return base_path() . '/' . ltrim($path, '/');
}

/**
 * URL de la parte publica, con prefijo de idioma.
 */
function lang_url(string $path = '', ?string $lang = null): string
{
    $lang = $lang ?? Lang::current();
    $path = trim($path, '/');

    return base_path() . '/' . $lang . ($path === '' ? '' : '/' . $path);
}

function admin_url(string $path = ''): string
{
    $path = trim($path, '/');

    return base_path() . '/admin' . ($path === '' ? '' : '/' . $path);
}

function asset(string $path): string
{
    return base_path() . '/assets/' . ltrim($path, '/');
}

/**
 * Resuelve rutas de imagen guardadas en base de datos.
 */
function img(string $path): string
{
    if ($path === '') {
        return asset('img/brand/Monogramma_AM_Oro_su_Avorio.svg');
    }

    if (str_starts_with($path, 'http://') || str_starts_with($path, 'https://')) {
        return $path;
    }

    return base_path() . '/' . ltrim($path, '/');
}

function t(string $key, array $args = []): string
{
    return Lang::get($key, $args);
}

function setting(string $key, string $default = ''): string
{
    return Settings::get($key, $default);
}

/**
 * Formatea un importe segun la moneda configurada.
 */
function money(float|string|null $amount): string
{
    $amount = (float) $amount;
    $symbol = Settings::get('currency_symbol', '&euro;');

    $formatted = number_format($amount, 2, ',', '.');

    return $formatted . ' ' . $symbol;
}

function csrf_field(): string
{
    return Csrf::field();
}

function slugify(string $text): string
{
    $text = (string) preg_replace('/[^\pL\pN]+/u', '-', $text);
    $transliterated = @iconv('UTF-8', 'ASCII//TRANSLIT', $text);

    if (is_string($transliterated)) {
        $text = $transliterated;
    }

    $text = (string) preg_replace('/[^A-Za-z0-9-]/', '', $text);
    $text = trim((string) preg_replace('/-+/', '-', $text), '-');

    return strtolower($text) ?: 'item';
}

/**
 * Recorta texto sin partir palabras.
 */
function excerpt(?string $html, int $length = 160): string
{
    $text = trim((string) preg_replace('/\s+/u', ' ', strip_tags((string) $html)));

    if (mb_strlen($text) <= $length) {
        return $text;
    }

    $cut = mb_substr($text, 0, $length);
    $lastSpace = mb_strrpos($cut, ' ');

    if ($lastSpace !== false) {
        $cut = mb_substr($cut, 0, $lastSpace);
    }

    return $cut . '…';
}

function format_date(?string $datetime, string $format = 'd/m/Y'): string
{
    if ($datetime === null || $datetime === '' || str_starts_with($datetime, '0000')) {
        return '';
    }

    $timestamp = strtotime($datetime);

    return $timestamp === false ? '' : date($format, $timestamp);
}

/**
 * Permite solo las etiquetas seguras en el contenido editable del panel.
 */
function safe_html(?string $html): string
{
    $allowed = '<p><br><strong><em><b><i><u><ul><ol><li><h2><h3><h4><a><blockquote>'
        . '<table><thead><tbody><tr><th><td><span><hr><figure><figcaption><img>';

    return strip_tags((string) $html, $allowed);
}
