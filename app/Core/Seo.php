<?php

declare(strict_types=1);

namespace App\Core;

final class Seo
{
    private static array $meta = [];
    private static array $schemas = [];

    public static function reset(): void
    {
        self::$meta    = [];
        self::$schemas = [];
    }

    public static function set(array $meta): void
    {
        self::$meta = $meta + self::$meta;
    }

    public static function title(): string
    {
        $suffix = Settings::get('meta_title_suffix', 'AMÉLIE');
        $title  = trim((string) (self::$meta['title'] ?? ''));

        if ($title === '' || $title === $suffix) {
            return $suffix;
        }

        return $title . ' · ' . $suffix;
    }

    public static function description(): string
    {
        return excerpt((string) (self::$meta['description'] ?? ''), 160);
    }

    public static function image(): string
    {
        $image = (string) (self::$meta['image'] ?? Settings::get('og_image', ''));

        return self::absolute(img($image));
    }

    public static function type(): string
    {
        return (string) (self::$meta['type'] ?? 'website');
    }

    public static function noindex(): bool
    {
        return !Settings::bool('robots_index', true) || !empty(self::$meta['noindex']);
    }

    /**
     * Ruta actual sin el prefijo de idioma, para construir las alternativas.
     */
    public static function canonicalPath(): string
    {
        return (string) (self::$meta['path'] ?? '');
    }

    public static function canonical(): string
    {
        return self::absolute(lang_url(self::canonicalPath()));
    }

    /**
     * Enlaces hreflang reciprocos, mas x-default.
     */
    public static function alternates(): array
    {
        $links = [];

        foreach (Lang::codes() as $code) {
            $links[$code] = self::absolute(lang_url(self::canonicalPath(), $code));
        }

        $links['x-default'] = self::absolute(lang_url(self::canonicalPath(), Lang::default()));

        return $links;
    }

    public static function absolute(string $path): string
    {
        if (str_starts_with($path, 'http://') || str_starts_with($path, 'https://')) {
            return $path;
        }

        $scheme = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') ? 'https' : 'http';
        $host   = (string) ($_SERVER['HTTP_HOST'] ?? 'localhost');

        return $scheme . '://' . $host . '/' . ltrim($path, '/');
    }

    public static function addSchema(array $schema): void
    {
        self::$schemas[] = $schema;
    }

    public static function schemas(): array
    {
        return self::$schemas;
    }

    public static function organizationSchema(): array
    {
        $social = array_values(array_filter([
            Settings::get('instagram_url'),
            Settings::get('facebook_url'),
            Settings::get('tiktok_url'),
            Settings::get('pinterest_url'),
        ]));

        return [
            '@context' => 'https://schema.org',
            '@type'    => 'Organization',
            'name'     => Settings::get('site_name', 'AMÉLIE'),
            'slogan'   => Settings::get('site_tagline', 'More Than Lingerie'),
            'url'      => self::absolute(lang_url('')),
            'logo'     => self::absolute(asset('img/brand/Logo_Oro_Trasparente.png')),
            'email'    => Settings::get('contact_email'),
            'sameAs'   => $social,
        ];
    }

    public static function breadcrumbSchema(array $trail): array
    {
        $items = [];

        foreach (array_values($trail) as $index => $crumb) {
            $items[] = [
                '@type'    => 'ListItem',
                'position' => $index + 1,
                'name'     => $crumb['name'],
                'item'     => self::absolute($crumb['url']),
            ];
        }

        return [
            '@context'        => 'https://schema.org',
            '@type'           => 'BreadcrumbList',
            'itemListElement' => $items,
        ];
    }
}
