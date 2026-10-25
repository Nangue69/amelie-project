<?php

declare(strict_types=1);

namespace App\Models;

use App\Core\Database;

final class Faq
{
    public static function active(string $lang): array
    {
        return Database::all(
            'SELECT f.`id`, ft.`question`, ft.`answer`
               FROM `faqs` f
               LEFT JOIN `faq_translations` ft ON ft.`faq_id` = f.`id` AND ft.`lang` = :lang
              WHERE f.`is_active` = 1
              ORDER BY f.`position`',
            ['lang' => $lang]
        );
    }

    public static function all(string $lang = 'es'): array
    {
        return Database::all(
            'SELECT f.*, ft.`question`
               FROM `faqs` f
               LEFT JOIN `faq_translations` ft ON ft.`faq_id` = f.`id` AND ft.`lang` = :lang
              ORDER BY f.`position`',
            ['lang' => $lang]
        );
    }

    public static function find(int $id): ?array
    {
        return Database::one('SELECT * FROM `faqs` WHERE `id` = :id', ['id' => $id]);
    }

    public static function translations(int $faqId): array
    {
        $rows   = Database::all('SELECT * FROM `faq_translations` WHERE `faq_id` = :id', ['id' => $faqId]);
        $byLang = [];

        foreach ($rows as $row) {
            $byLang[$row['lang']] = $row;
        }

        return $byLang;
    }

    public static function saveTranslation(int $faqId, string $lang, array $data): void
    {
        $exists = Database::value(
            'SELECT `id` FROM `faq_translations` WHERE `faq_id` = :f AND `lang` = :l',
            ['f' => $faqId, 'l' => $lang]
        );

        if ($exists) {
            Database::update('faq_translations', $data, '`id` = :id', ['id' => (int) $exists]);

            return;
        }

        Database::insert('faq_translations', $data + ['faq_id' => $faqId, 'lang' => $lang]);
    }
}
