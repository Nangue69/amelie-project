<?php

declare(strict_types=1);

namespace App\Core;

final class Request
{
    private array $params = [];

    public function method(): string
    {
        return strtoupper($_SERVER['REQUEST_METHOD'] ?? 'GET');
    }

    public function isPost(): bool
    {
        return $this->method() === 'POST';
    }

    /**
     * Ruta solicitada, ya sin el subdirectorio de instalacion ni la query string.
     */
    public function path(): string
    {
        $uri  = parse_url($_SERVER['REQUEST_URI'] ?? '/', PHP_URL_PATH) ?: '/';
        $base = base_path();

        if ($base !== '' && str_starts_with($uri, $base)) {
            $uri = substr($uri, strlen($base));
        }

        $uri = '/' . trim(rawurldecode($uri), '/');

        return $uri === '/' ? '/' : rtrim($uri, '/');
    }

    public function input(string $key, mixed $default = null): mixed
    {
        return $_POST[$key] ?? $_GET[$key] ?? $default;
    }

    public function text(string $key, string $default = ''): string
    {
        $value = $this->input($key, $default);

        return is_scalar($value) ? self::clean((string) $value) : $default;
    }

    /**
     * Descarta bytes que no formen UTF-8 valido. El navegador siempre envia
     * UTF-8, pero una peticion manipulada no tiene por que hacerlo y MySQL
     * rechazaria la insercion.
     */
    public static function clean(string $value): string
    {
        if (!mb_check_encoding($value, 'UTF-8')) {
            $value = mb_convert_encoding($value, 'UTF-8', 'UTF-8');
        }

        return trim($value);
    }

    /**
     * Sanea $_GET y $_POST de una vez, incluidos los campos en forma de array.
     */
    public static function sanitizeGlobals(): void
    {
        $walk = static function (array $input) use (&$walk): array {
            $clean = [];

            foreach ($input as $key => $value) {
                $key = is_string($key) ? self::clean($key) : $key;

                if (is_array($value)) {
                    $clean[$key] = $walk($value);

                    continue;
                }

                $clean[$key] = is_string($value) ? self::clean($value) : $value;
            }

            return $clean;
        };

        $_GET  = $walk($_GET);
        $_POST = $walk($_POST);
    }

    public function int(string $key, int $default = 0): int
    {
        $value = $this->input($key, $default);

        return is_numeric($value) ? (int) $value : $default;
    }

    public function all(): array
    {
        return $_POST + $_GET;
    }

    public function isAjax(): bool
    {
        return strtolower($_SERVER['HTTP_X_REQUESTED_WITH'] ?? '') === 'xmlhttprequest';
    }

    public function setParams(array $params): void
    {
        $this->params = $params;
    }

    public function param(string $key, string $default = ''): string
    {
        return isset($this->params[$key]) ? (string) $this->params[$key] : $default;
    }

    public function ip(): string
    {
        return (string) ($_SERVER['REMOTE_ADDR'] ?? '');
    }

    public function currentUrl(): string
    {
        return $this->path();
    }
}
