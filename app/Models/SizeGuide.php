<?php

declare(strict_types=1);

namespace App\Models;

use App\Core\Database;

final class SizeGuide
{
    public const TITLES = [
        'bra'    => ['es' => 'Sujetador', 'en' => 'Bra',    'nl' => 'Bh',     'it' => 'Reggiseno'],
        'brief'  => ['es' => 'Tanga',     'en' => 'Brief',  'nl' => 'Slip',   'it' => 'Slip'],
        'corset' => ['es' => 'Corsé',     'en' => 'Corset', 'nl' => 'Korset', 'it' => 'Corsetto'],
    ];

    public const HEADERS = [
        'bust'      => ['es' => 'Contorno de pecho', 'en' => 'Bust',      'nl' => 'Borstomvang',      'it' => 'Seno'],
        'underbust' => ['es' => 'Bajo pecho',        'en' => 'Underbust', 'nl' => 'Onderborst',       'it' => 'Sottoseno'],
        'waist'     => ['es' => 'Cintura',           'en' => 'Waist',     'nl' => 'Taille',           'it' => 'Vita'],
        'hip'       => ['es' => 'Cadera',            'en' => 'Hip',       'nl' => 'Heup',             'it' => 'Fianchi'],
    ];

    /**
     * Guias con sus filas, listas para pintar una tabla por bloque.
     */
    public static function allWithRows(): array
    {
        $guides = Database::all('SELECT * FROM `size_guides` ORDER BY `position`');

        foreach ($guides as $index => $guide) {
            $guides[$index]['rows'] = Database::all(
                'SELECT * FROM `size_guide_rows` WHERE `guide_id` = :id ORDER BY `position`',
                ['id' => $guide['id']]
            );
        }

        return $guides;
    }

    public static function rows(int $guideId): array
    {
        return Database::all(
            'SELECT * FROM `size_guide_rows` WHERE `guide_id` = :id ORDER BY `position`',
            ['id' => $guideId]
        );
    }

    public static function title(string $key, string $lang): string
    {
        return self::TITLES[$key][$lang] ?? self::TITLES[$key]['en'] ?? $key;
    }

    public static function header(string $key, string $lang): string
    {
        return self::HEADERS[$key][$lang] ?? self::HEADERS[$key]['en'] ?? $key;
    }

    /**
     * Columnas de medidas que tienen algun valor en la guia.
     */
    public static function usedColumns(array $rows): array
    {
        $columns = [];

        foreach (array_keys(self::HEADERS) as $column) {
            foreach ($rows as $row) {
                if (trim((string) ($row[$column] ?? '')) !== '') {
                    $columns[] = $column;

                    break;
                }
            }
        }

        return $columns;
    }

    public static function updateRow(int $rowId, array $data): void
    {
        Database::update('size_guide_rows', $data, '`id` = :id', ['id' => $rowId]);
    }
}
