<?php

declare(strict_types=1);

namespace App\Models;

use App\Core\Database;

final class Product
{
    private const LIST_FIELDS = 'p.`id`, p.`slug`, p.`sku`, p.`price`, p.`compare_price`, p.`size_mode`,
                                 p.`is_featured`, p.`category_id`, p.`created_at`,
                                 pt.`name`, pt.`tagline`, pt.`short_description`';

    private const PRIMARY_IMAGE = '(SELECT pi.`path` FROM `product_images` pi
                                    WHERE pi.`product_id` = p.`id`
                                    ORDER BY pi.`is_primary` DESC, pi.`position` LIMIT 1) AS image';

    private const SECOND_IMAGE = '(SELECT pi.`path` FROM `product_images` pi
                                   WHERE pi.`product_id` = p.`id`
                                   ORDER BY pi.`is_primary` DESC, pi.`position` LIMIT 1 OFFSET 1) AS image_hover';

    private const STOCK = '(SELECT COALESCE(SUM(v.`stock`), 0) FROM `product_variants` v
                             WHERE v.`product_id` = p.`id` AND v.`is_active` = 1) AS stock';

    /**
     * Listado publico con filtro de categoria y orden.
     */
    public static function published(string $lang, ?string $categorySlug = null, string $sort = 'featured'): array
    {
        $params = ['lang' => $lang];
        $where  = "p.`status` = 'published'";

        if ($categorySlug !== null && $categorySlug !== '') {
            $where .= ' AND c.`slug` = :category';
            $params['category'] = $categorySlug;
        }

        $order = match ($sort) {
            'newest'     => 'p.`created_at` DESC',
            'price_asc'  => 'p.`price` ASC',
            'price_desc' => 'p.`price` DESC',
            default      => 'p.`is_featured` DESC, p.`position` ASC',
        };

        return Database::all(
            'SELECT ' . self::LIST_FIELDS . ', ' . self::PRIMARY_IMAGE . ', ' . self::SECOND_IMAGE . ', ' . self::STOCK . '
               FROM `products` p
               LEFT JOIN `product_translations` pt ON pt.`product_id` = p.`id` AND pt.`lang` = :lang
               LEFT JOIN `categories` c ON c.`id` = p.`category_id`
              WHERE ' . $where . '
              ORDER BY ' . $order,
            $params
        );
    }

    public static function featured(string $lang, int $limit = 4): array
    {
        $limit = max(1, min($limit, 24));

        return Database::all(
            'SELECT ' . self::LIST_FIELDS . ', ' . self::PRIMARY_IMAGE . ', ' . self::SECOND_IMAGE . ', ' . self::STOCK . '
               FROM `products` p
               LEFT JOIN `product_translations` pt ON pt.`product_id` = p.`id` AND pt.`lang` = :lang
              WHERE p.`status` = "published"
              ORDER BY p.`is_featured` DESC, p.`position`
              LIMIT ' . $limit,
            ['lang' => $lang]
        );
    }

    public static function findBySlug(string $slug, string $lang): ?array
    {
        return Database::one(
            'SELECT p.*, pt.`name`, pt.`tagline`, pt.`short_description`, pt.`description`,
                    pt.`composition`, pt.`care`, pt.`shipping_returns`,
                    pt.`meta_title`, pt.`meta_description`,
                    c.`slug` AS category_slug, ct.`name` AS category_name, ' . self::STOCK . '
               FROM `products` p
               LEFT JOIN `product_translations` pt ON pt.`product_id` = p.`id` AND pt.`lang` = :lang
               LEFT JOIN `categories` c ON c.`id` = p.`category_id`
               LEFT JOIN `category_translations` ct ON ct.`category_id` = c.`id` AND ct.`lang` = :lang2
              WHERE p.`slug` = :slug AND p.`status` = "published"
              LIMIT 1',
            ['slug' => $slug, 'lang' => $lang, 'lang2' => $lang]
        );
    }

    public static function find(int $id): ?array
    {
        return Database::one('SELECT * FROM `products` WHERE `id` = :id', ['id' => $id]);
    }

    public static function images(int $productId): array
    {
        return Database::all(
            'SELECT * FROM `product_images`
              WHERE `product_id` = :id
              ORDER BY `is_primary` DESC, `position`',
            ['id' => $productId]
        );
    }

    public static function variants(int $productId, bool $onlyActive = true): array
    {
        $where = $onlyActive ? ' AND `is_active` = 1' : '';

        return Database::all(
            'SELECT * FROM `product_variants`
              WHERE `product_id` = :id' . $where . '
              ORDER BY `position`, `id`',
            ['id' => $productId]
        );
    }

    public static function variant(int $variantId): ?array
    {
        return Database::one('SELECT * FROM `product_variants` WHERE `id` = :id', ['id' => $variantId]);
    }

    public static function related(int $productId, string $lang, int $limit = 4): array
    {
        $limit = max(1, min($limit, 12));

        return Database::all(
            'SELECT ' . self::LIST_FIELDS . ', ' . self::PRIMARY_IMAGE . ', ' . self::SECOND_IMAGE . ', ' . self::STOCK . '
               FROM `products` p
               LEFT JOIN `product_translations` pt ON pt.`product_id` = p.`id` AND pt.`lang` = :lang
              WHERE p.`status` = "published" AND p.`id` <> :id
              ORDER BY p.`is_featured` DESC, p.`position`
              LIMIT ' . $limit,
            ['lang' => $lang, 'id' => $productId]
        );
    }

    public static function search(string $term, string $lang): array
    {
        $like = '%' . $term . '%';

        return Database::all(
            'SELECT ' . self::LIST_FIELDS . ', ' . self::PRIMARY_IMAGE . ', ' . self::SECOND_IMAGE . ', ' . self::STOCK . '
               FROM `products` p
               LEFT JOIN `product_translations` pt ON pt.`product_id` = p.`id` AND pt.`lang` = :lang
              WHERE p.`status` = "published"
                AND (pt.`name` LIKE :a OR pt.`short_description` LIKE :b OR pt.`description` LIKE :c OR p.`sku` LIKE :d)
              ORDER BY p.`is_featured` DESC, p.`position`
              LIMIT 24',
            ['lang' => $lang, 'a' => $like, 'b' => $like, 'c' => $like, 'd' => $like]
        );
    }

    public static function slugs(): array
    {
        return Database::all('SELECT `slug`, `updated_at` FROM `products` WHERE `status` = "published"');
    }

    // ------------------------------------------------------------------
    // Panel de administracion
    // ------------------------------------------------------------------

    public static function adminList(string $lang = 'es'): array
    {
        return Database::all(
            'SELECT p.`id`, p.`slug`, p.`sku`, p.`price`, p.`status`, p.`is_featured`, p.`size_mode`,
                    pt.`name`, ct.`name` AS category_name, ' . self::PRIMARY_IMAGE . ', ' . self::STOCK . '
               FROM `products` p
               LEFT JOIN `product_translations` pt ON pt.`product_id` = p.`id` AND pt.`lang` = :lang
               LEFT JOIN `categories` c ON c.`id` = p.`category_id`
               LEFT JOIN `category_translations` ct ON ct.`category_id` = c.`id` AND ct.`lang` = :lang2
              ORDER BY p.`position`, p.`id`',
            ['lang' => $lang, 'lang2' => $lang]
        );
    }

    public static function translations(int $productId): array
    {
        $rows   = Database::all('SELECT * FROM `product_translations` WHERE `product_id` = :id', ['id' => $productId]);
        $byLang = [];

        foreach ($rows as $row) {
            $byLang[$row['lang']] = $row;
        }

        return $byLang;
    }

    public static function saveTranslation(int $productId, string $lang, array $data): void
    {
        $exists = Database::value(
            'SELECT `id` FROM `product_translations` WHERE `product_id` = :p AND `lang` = :l',
            ['p' => $productId, 'l' => $lang]
        );

        if ($exists) {
            Database::update('product_translations', $data, '`id` = :id', ['id' => (int) $exists]);

            return;
        }

        Database::insert('product_translations', $data + ['product_id' => $productId, 'lang' => $lang]);
    }

    public static function lowStock(int $threshold = 5): array
    {
        return Database::all(
            'SELECT v.`id`, v.`sku`, v.`stock`, v.`size`, v.`size_bra`, v.`size_brief`,
                    p.`slug`, p.`size_mode`, pt.`name`
               FROM `product_variants` v
               JOIN `products` p ON p.`id` = v.`product_id`
               LEFT JOIN `product_translations` pt ON pt.`product_id` = p.`id` AND pt.`lang` = "es"
              WHERE v.`is_active` = 1 AND v.`stock` <= :t AND p.`status` = "published"
              ORDER BY v.`stock`, p.`position`
              LIMIT 25',
            ['t' => $threshold]
        );
    }

    public static function countByStatus(string $status): int
    {
        return (int) Database::value(
            'SELECT COUNT(*) FROM `products` WHERE `status` = :s',
            ['s' => $status],
            0
        );
    }
}
