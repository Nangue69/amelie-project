<?php

declare(strict_types=1);

namespace App\Models;

use App\Core\Database;

final class Page
{
    public static function findBySlug(string $slug, string $lang): ?array
    {
        return Database::one(
            'SELECT p.*, pt.`title`, pt.`intro`, pt.`body`, pt.`meta_title`, pt.`meta_description`
               FROM `pages` p
               LEFT JOIN `page_translations` pt ON pt.`page_id` = p.`id` AND pt.`lang` = :lang
              WHERE p.`slug` = :slug AND p.`is_active` = 1
              LIMIT 1',
            ['slug' => $slug, 'lang' => $lang]
        );
    }

    public static function title(string $slug, string $lang): string
    {
        return (string) Database::value(
            'SELECT pt.`title`
               FROM `pages` p
               JOIN `page_translations` pt ON pt.`page_id` = p.`id` AND pt.`lang` = :lang
              WHERE p.`slug` = :slug',
            ['slug' => $slug, 'lang' => $lang],
            $slug
        );
    }

    /**
     * Enlaces de pie de pagina agrupados por bloque.
     */
    public static function footerLinks(string $lang): array
    {
        $rows = Database::all(
            'SELECT p.`slug`, pt.`title`
               FROM `pages` p
               LEFT JOIN `page_translations` pt ON pt.`page_id` = p.`id` AND pt.`lang` = :lang
              WHERE p.`is_active` = 1
              ORDER BY p.`position`',
            ['lang' => $lang]
        );

        $titles = [];

        foreach ($rows as $row) {
            $titles[$row['slug']] = (string) ($row['title'] ?? $row['slug']);
        }

        $pick = static function (array $slugs) use ($titles): array {
            $links = [];

            foreach ($slugs as $slug) {
                if (isset($titles[$slug])) {
                    $links[] = ['slug' => $slug, 'title' => $titles[$slug]];
                }
            }

            return $links;
        };

        return [
            'help'  => $pick(['size-guide', 'shipping-returns', 'faq', 'contact']),
            'legal' => $pick(['privacy', 'cookies', 'terms']),
        ];
    }

    public static function all(string $lang = 'es'): array
    {
        return Database::all(
            'SELECT p.*, pt.`title`
               FROM `pages` p
               LEFT JOIN `page_translations` pt ON pt.`page_id` = p.`id` AND pt.`lang` = :lang
              ORDER BY p.`position`',
            ['lang' => $lang]
        );
    }

    public static function find(int $id): ?array
    {
        return Database::one('SELECT * FROM `pages` WHERE `id` = :id', ['id' => $id]);
    }

    public static function translations(int $pageId): array
    {
        $rows   = Database::all('SELECT * FROM `page_translations` WHERE `page_id` = :id', ['id' => $pageId]);
        $byLang = [];

        foreach ($rows as $row) {
            $byLang[$row['lang']] = $row;
        }

        return $byLang;
    }

    public static function saveTranslation(int $pageId, string $lang, array $data): void
    {
        $exists = Database::value(
            'SELECT `id` FROM `page_translations` WHERE `page_id` = :p AND `lang` = :l',
            ['p' => $pageId, 'l' => $lang]
        );

        if ($exists) {
            Database::update('page_translations', $data, '`id` = :id', ['id' => (int) $exists]);

            return;
        }

        Database::insert('page_translations', $data + ['page_id' => $pageId, 'lang' => $lang]);
    }

    public static function slugs(): array
    {
        return Database::all('SELECT `slug` FROM `pages` WHERE `is_active` = 1');
    }
}
