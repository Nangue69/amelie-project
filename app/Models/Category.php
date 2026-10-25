<?php

declare(strict_types=1);

namespace App\Models;

use App\Core\Database;

final class Category
{
    public static function active(string $lang): array
    {
        return Database::all(
            'SELECT c.`id`, c.`slug`, c.`image`, ct.`name`, ct.`description`,
                    (SELECT COUNT(*) FROM `products` p
                      WHERE p.`category_id` = c.`id` AND p.`status` = "published") AS total
               FROM `categories` c
               LEFT JOIN `category_translations` ct ON ct.`category_id` = c.`id` AND ct.`lang` = :lang
              WHERE c.`is_active` = 1
              ORDER BY c.`position`',
            ['lang' => $lang]
        );
    }

    public static function findBySlug(string $slug, string $lang): ?array
    {
        return Database::one(
            'SELECT c.*, ct.`name`, ct.`description`, ct.`meta_title`, ct.`meta_description`
               FROM `categories` c
               LEFT JOIN `category_translations` ct ON ct.`category_id` = c.`id` AND ct.`lang` = :lang
              WHERE c.`slug` = :slug AND c.`is_active` = 1
              LIMIT 1',
            ['slug' => $slug, 'lang' => $lang]
        );
    }

    public static function all(string $lang = 'es'): array
    {
        return Database::all(
            'SELECT c.*, ct.`name`
               FROM `categories` c
               LEFT JOIN `category_translations` ct ON ct.`category_id` = c.`id` AND ct.`lang` = :lang
              ORDER BY c.`position`',
            ['lang' => $lang]
        );
    }

    public static function find(int $id): ?array
    {
        return Database::one('SELECT * FROM `categories` WHERE `id` = :id', ['id' => $id]);
    }

    public static function translations(int $categoryId): array
    {
        $rows   = Database::all('SELECT * FROM `category_translations` WHERE `category_id` = :id', ['id' => $categoryId]);
        $byLang = [];

        foreach ($rows as $row) {
            $byLang[$row['lang']] = $row;
        }

        return $byLang;
    }

    public static function saveTranslation(int $categoryId, string $lang, array $data): void
    {
        $exists = Database::value(
            'SELECT `id` FROM `category_translations` WHERE `category_id` = :c AND `lang` = :l',
            ['c' => $categoryId, 'l' => $lang]
        );

        if ($exists) {
            Database::update('category_translations', $data, '`id` = :id', ['id' => (int) $exists]);

            return;
        }

        Database::insert('category_translations', $data + ['category_id' => $categoryId, 'lang' => $lang]);
    }
}
