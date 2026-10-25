<?php

declare(strict_types=1);

namespace App\Core;

final class View
{
    private static array $shared = [];
    private static array $sections = [];

    public static function share(string $key, mixed $value): void
    {
        self::$shared[$key] = $value;
    }

    public static function shared(): array
    {
        return self::$shared;
    }

    public static function render(string $template, array $data = [], ?string $layout = null): string
    {
        $content = self::capture($template, $data);

        if ($layout === null) {
            return $content;
        }

        self::$sections['content'] = $content;

        return self::capture('layouts/' . $layout, $data);
    }

    public static function partial(string $template, array $data = []): string
    {
        return self::capture('partials/' . $template, $data);
    }

    public static function section(string $name): string
    {
        return self::$sections[$name] ?? '';
    }

    public static function setSection(string $name, string $value): void
    {
        self::$sections[$name] = $value;
    }

    private static function capture(string $template, array $data): string
    {
        $file = APP_PATH . '/Views/' . str_replace('.', '/', $template) . '.php';

        if (!is_file($file)) {
            throw new \RuntimeException("Vista no encontrada: {$template}");
        }

        extract(self::$shared, EXTR_SKIP);
        extract($data, EXTR_OVERWRITE);

        ob_start();

        try {
            require $file;
        } catch (\Throwable $e) {
            ob_end_clean();

            throw $e;
        }

        return (string) ob_get_clean();
    }
}
