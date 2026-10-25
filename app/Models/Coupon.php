<?php

declare(strict_types=1);

namespace App\Models;

use App\Core\Database;

final class Coupon
{
    public static function findUsable(string $code): ?array
    {
        $coupon = Database::one(
            'SELECT * FROM `coupons`
              WHERE `code` = :code AND `is_active` = 1
              LIMIT 1',
            ['code' => strtoupper(trim($code))]
        );

        if ($coupon === null) {
            return null;
        }

        $today = date('Y-m-d');

        if ($coupon['starts_at'] !== null && $coupon['starts_at'] > $today) {
            return null;
        }

        if ($coupon['ends_at'] !== null && $coupon['ends_at'] < $today) {
            return null;
        }

        if ($coupon['usage_limit'] !== null && (int) $coupon['used_count'] >= (int) $coupon['usage_limit']) {
            return null;
        }

        return $coupon;
    }

    public static function all(): array
    {
        return Database::all('SELECT * FROM `coupons` ORDER BY `is_active` DESC, `code`');
    }

    public static function find(int $id): ?array
    {
        return Database::one('SELECT * FROM `coupons` WHERE `id` = :id', ['id' => $id]);
    }

    public static function label(array $coupon): string
    {
        return match ($coupon['type']) {
            'percent'       => rtrim(rtrim(number_format((float) $coupon['value'], 2, ',', '.'), '0'), ',') . ' %',
            'fixed'         => money((float) $coupon['value']),
            'free_shipping' => 'Envío gratuito',
            default         => '',
        };
    }
}
