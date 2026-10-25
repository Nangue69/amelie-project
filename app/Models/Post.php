<?php

declare(strict_types=1);

namespace App\Models;

use App\Core\Database;

final class Post
{
    public static function published(string $lang, int $limit = 12): array
    {
        $limit = max(1, min($limit, 50));

        return Database::all(
            'SELECT p.`id`, p.`slug`, p.`cover_image`, p.`published_at`,
                    pt.`title`, pt.`excerpt`
               FROM `posts` p
               LEFT JOIN `post_translations` pt ON pt.`post_id` = p.`id` AND pt.`lang` = :lang
              WHERE p.`status` = "published" AND p.`published_at` <= NOW()
              ORDER BY p.`published_at` DESC
              LIMIT ' . $limit,
            ['lang' => $lang]
        );
    }

    public static function findBySlug(string $slug, string $lang): ?array
    {
        return Database::one(
            'SELECT p.*, pt.`title`, pt.`excerpt`, pt.`body`, pt.`meta_title`, pt.`meta_description`,
                    u.`name` AS author_name
               FROM `posts` p
               LEFT JOIN `post_translations` pt ON pt.`post_id` = p.`id` AND pt.`lang` = :lang
               LEFT JOIN `users` u ON u.`id` = p.`author_id`
              WHERE p.`slug` = :slug AND p.`status` = "published" AND p.`published_at` <= NOW()
              LIMIT 1',
            ['slug' => $slug, 'lang' => $lang]
        );
    }

    public static function others(int $postId, string $lang, int $limit = 2): array
    {
        $limit = max(1, min($limit, 6));

        return Database::all(
            'SELECT p.`id`, p.`slug`, p.`cover_image`, p.`published_at`, pt.`title`, pt.`excerpt`
               FROM `posts` p
               LEFT JOIN `post_translations` pt ON pt.`post_id` = p.`id` AND pt.`lang` = :lang
              WHERE p.`status` = "published" AND p.`published_at` <= NOW() AND p.`id` <> :id
              ORDER BY p.`published_at` DESC
              LIMIT ' . $limit,
            ['lang' => $lang, 'id' => $postId]
        );
    }

    public static function all(string $lang = 'es'): array
    {
        return Database::all(
            'SELECT p.*, pt.`title`
               FROM `posts` p
               LEFT JOIN `post_translations` pt ON pt.`post_id` = p.`id` AND pt.`lang` = :lang
              ORDER BY COALESCE(p.`published_at`, p.`created_at`) DESC',
            ['lang' => $lang]
        );
    }

    public static function find(int $id): ?array
    {
        return Database::one('SELECT * FROM `posts` WHERE `id` = :id', ['id' => $id]);
    }

    public static function translations(int $postId): array
    {
        $rows   = Database::all('SELECT * FROM `post_translations` WHERE `post_id` = :id', ['id' => $postId]);
        $byLang = [];

        foreach ($rows as $row) {
            $byLang[$row['lang']] = $row;
        }

        return $byLang;
    }

    public static function saveTranslation(int $postId, string $lang, array $data): void
    {
        $exists = Database::value(
            'SELECT `id` FROM `post_translations` WHERE `post_id` = :p AND `lang` = :l',
            ['p' => $postId, 'l' => $lang]
        );

        if ($exists) {
            Database::update('post_translations', $data, '`id` = :id', ['id' => (int) $exists]);

            return;
        }

        Database::insert('post_translations', $data + ['post_id' => $postId, 'lang' => $lang]);
    }

    public static function slugs(): array
    {
        return Database::all(
            'SELECT `slug`, `updated_at` FROM `posts`
              WHERE `status` = "published" AND `published_at` <= NOW()'
        );
    }
}
