<?php

declare(strict_types=1);

namespace App\Models;

use App\Core\Database;

final class Shipping
{
    /**
     * Zona aplicable a un pais. '*' actua como tarifa de resto del mundo.
     */
    public static function rateForCountry(string $country): ?array
    {
        $country = strtoupper(trim($country));

        if ($country === '') {
            return null;
        }

        $rates = self::active();

        foreach ($rates as $rate) {
            $countries = array_map(
                static fn (string $c): string => strtoupper(trim($c)),
                explode(',', (string) $rate['countries'])
            );

            if (in_array($country, $countries, true)) {
                return $rate;
            }
        }

        foreach ($rates as $rate) {
            if (trim((string) $rate['countries']) === '*') {
                return $rate;
            }
        }

        return null;
    }

    public static function active(): array
    {
        return Database::all(
            'SELECT * FROM `shipping_rates` WHERE `is_active` = 1 ORDER BY `position`, `id`'
        );
    }

    public static function all(): array
    {
        return Database::all('SELECT * FROM `shipping_rates` ORDER BY `position`, `id`');
    }

    public static function find(int $id): ?array
    {
        return Database::one('SELECT * FROM `shipping_rates` WHERE `id` = :id', ['id' => $id]);
    }

    /**
     * Paises seleccionables en el checkout, ordenados por relevancia de mercado.
     */
    public static function countries(): array
    {
        return [
            'ES' => 'España',
            'NL' => 'Países Bajos',
            'IT' => 'Italia',
            'BE' => 'Bélgica',
            'DE' => 'Alemania',
            'FR' => 'Francia',
            'PT' => 'Portugal',
            'AT' => 'Austria',
            'IE' => 'Irlanda',
            'LU' => 'Luxemburgo',
            'DK' => 'Dinamarca',
            'SE' => 'Suecia',
            'FI' => 'Finlandia',
            'PL' => 'Polonia',
            'CZ' => 'Chequia',
            'GR' => 'Grecia',
            'HU' => 'Hungría',
            'RO' => 'Rumanía',
            'HR' => 'Croacia',
            'SI' => 'Eslovenia',
            'SK' => 'Eslovaquia',
            'BG' => 'Bulgaria',
            'EE' => 'Estonia',
            'LV' => 'Letonia',
            'LT' => 'Lituania',
            'CY' => 'Chipre',
            'MT' => 'Malta',
        ];
    }

    public static function countryName(string $code): string
    {
        return self::countries()[strtoupper($code)] ?? strtoupper($code);
    }
}
