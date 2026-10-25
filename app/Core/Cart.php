<?php

declare(strict_types=1);

namespace App\Core;

use App\Models\Coupon;
use App\Models\Shipping;

final class Cart
{
    private const KEY        = '_cart';
    private const COUPON_KEY = '_coupon';
    private const MAX_QTY    = 10;

    /**
     * Lineas en bruto guardadas en sesion: variant_id => cantidad.
     */
    private static function raw(): array
    {
        $cart = Session::get(self::KEY, []);

        return is_array($cart) ? $cart : [];
    }

    private static function save(array $cart): void
    {
        Session::set(self::KEY, $cart);
    }

    /**
     * Anade unidades de una variante. Devuelve false si no hay stock suficiente.
     */
    public static function add(int $variantId, int $quantity = 1): bool
    {
        $variant = self::findVariant($variantId);

        if ($variant === null) {
            return false;
        }

        $cart    = self::raw();
        $current = (int) ($cart[$variantId] ?? 0);
        $wanted  = min($current + max(1, $quantity), self::MAX_QTY);

        if ($wanted > (int) $variant['stock']) {
            $wanted = (int) $variant['stock'];
        }

        if ($wanted < 1) {
            return false;
        }

        $cart[$variantId] = $wanted;
        self::save($cart);

        return true;
    }

    public static function update(int $variantId, int $quantity): void
    {
        $cart = self::raw();

        if ($quantity < 1) {
            unset($cart[$variantId]);
            self::save($cart);

            return;
        }

        $variant = self::findVariant($variantId);

        if ($variant === null) {
            unset($cart[$variantId]);
            self::save($cart);

            return;
        }

        $cart[$variantId] = min($quantity, self::MAX_QTY, (int) $variant['stock']);

        if ($cart[$variantId] < 1) {
            unset($cart[$variantId]);
        }

        self::save($cart);
    }

    public static function remove(int $variantId): void
    {
        $cart = self::raw();
        unset($cart[$variantId]);
        self::save($cart);
    }

    public static function clear(): void
    {
        Session::forget(self::KEY);
        Session::forget(self::COUPON_KEY);
    }

    private static function findVariant(int $variantId): ?array
    {
        return Database::one(
            'SELECT v.*, p.`status`
               FROM `product_variants` v
               JOIN `products` p ON p.`id` = v.`product_id`
              WHERE v.`id` = :id AND v.`is_active` = 1 AND p.`status` = "published"
              LIMIT 1',
            ['id' => $variantId]
        );
    }

    /**
     * Lineas del carrito revalidadas contra la base de datos.
     * Si una variante ya no existe o se ha quedado sin stock, se corrige aqui.
     */
    public static function items(?string $lang = null): array
    {
        $cart = self::raw();

        if ($cart === []) {
            return [];
        }

        $lang = $lang ?? Lang::current();
        [$holders, $params] = Database::inClause(array_keys($cart), 'v');
        $params['lang'] = $lang;

        $rows = Database::all(
            "SELECT v.`id`          AS variant_id,
                    v.`stock`,
                    v.`size`,
                    v.`size_bra`,
                    v.`size_brief`,
                    v.`sku`,
                    v.`price_override`,
                    p.`id`          AS product_id,
                    p.`slug`,
                    p.`price`,
                    p.`size_mode`,
                    pt.`name`,
                    (SELECT pi.`path`
                       FROM `product_images` pi
                      WHERE pi.`product_id` = p.`id`
                      ORDER BY pi.`is_primary` DESC, pi.`position`
                      LIMIT 1) AS image
               FROM `product_variants` v
               JOIN `products` p  ON p.`id` = v.`product_id`
               LEFT JOIN `product_translations` pt
                      ON pt.`product_id` = p.`id` AND pt.`lang` = :lang
              WHERE v.`id` IN ({$holders})
                AND v.`is_active` = 1
                AND p.`status` = 'published'",
            $params
        );

        $items   = [];
        $changed = false;

        foreach ($rows as $row) {
            $variantId = (int) $row['variant_id'];
            $quantity  = min((int) $cart[$variantId], (int) $row['stock']);

            if ($quantity !== (int) $cart[$variantId]) {
                $changed = true;
            }

            if ($quantity < 1) {
                unset($cart[$variantId]);

                continue;
            }

            $price = $row['price_override'] !== null
                ? (float) $row['price_override']
                : (float) $row['price'];

            $items[] = [
                'variant_id' => $variantId,
                'product_id' => (int) $row['product_id'],
                'slug'       => (string) $row['slug'],
                'name'       => (string) ($row['name'] ?? $row['slug']),
                'sku'        => (string) $row['sku'],
                'image'      => (string) ($row['image'] ?? ''),
                'size_label' => self::sizeLabel($row),
                'unit_price' => $price,
                'quantity'   => $quantity,
                'line_total' => round($price * $quantity, 2),
                'stock'      => (int) $row['stock'],
            ];

            $cart[$variantId] = $quantity;
        }

        // Variantes que ya no existen en el catalogo.
        $found = array_column($items, 'variant_id');

        foreach (array_keys($cart) as $variantId) {
            if (!in_array((int) $variantId, $found, true)) {
                unset($cart[$variantId]);
                $changed = true;
            }
        }

        if ($changed) {
            self::save($cart);
        }

        return $items;
    }

    public static function sizeLabel(array $row): string
    {
        if (($row['size_mode'] ?? 'single') === 'bra_brief') {
            return trim(t('product.size_bra') . ' ' . $row['size_bra'] . ' · ' . t('product.size_brief') . ' ' . $row['size_brief']);
        }

        return trim(t('product.size') . ' ' . (string) $row['size']);
    }

    public static function count(): int
    {
        $total = 0;

        foreach (self::raw() as $quantity) {
            $total += (int) $quantity;
        }

        return $total;
    }

    public static function isEmpty(): bool
    {
        return self::raw() === [];
    }

    public static function subtotal(?array $items = null): float
    {
        $items ??= self::items();
        $total = 0.0;

        foreach ($items as $item) {
            $total += (float) $item['line_total'];
        }

        return round($total, 2);
    }

    public static function applyCoupon(string $code): array
    {
        $coupon = Coupon::findUsable($code);

        if ($coupon === null) {
            return ['ok' => false, 'message' => t('msg.coupon_invalid')];
        }

        $subtotal = self::subtotal();

        if ($subtotal < (float) $coupon['min_amount']) {
            return ['ok' => false, 'message' => t('msg.coupon_min', [money((float) $coupon['min_amount'])])];
        }

        Session::set(self::COUPON_KEY, strtoupper($coupon['code']));

        return ['ok' => true, 'message' => t('msg.coupon_ok')];
    }

    public static function removeCoupon(): void
    {
        Session::forget(self::COUPON_KEY);
    }

    public static function couponCode(): string
    {
        $code = Session::get(self::COUPON_KEY, '');

        return is_string($code) ? $code : '';
    }

    public static function coupon(): ?array
    {
        $code = self::couponCode();

        return $code === '' ? null : Coupon::findUsable($code);
    }

    /**
     * Calcula subtotal, envio, descuento y total para un pais dado.
     */
    public static function totals(?string $country = null, ?array $items = null): array
    {
        $items    ??= self::items();
        $subtotal   = self::subtotal($items);
        $coupon     = self::coupon();
        $discount   = 0.0;
        $freeShip   = false;

        if ($coupon !== null && $subtotal >= (float) $coupon['min_amount']) {
            if ($coupon['type'] === 'percent') {
                $discount = round($subtotal * (float) $coupon['value'] / 100, 2);
            } elseif ($coupon['type'] === 'fixed') {
                $discount = min(round((float) $coupon['value'], 2), $subtotal);
            } else {
                $freeShip = true;
            }
        } else {
            $coupon = null;
        }

        $shipping = 0.0;
        $rate     = null;

        if ($country !== null && $items !== []) {
            $rate     = Shipping::rateForCountry($country);
            $shipping = $rate === null ? 0.0 : (float) $rate['price'];

            $freeFrom = $rate['free_from'] ?? null;
            $base     = $subtotal - $discount;

            if ($freeShip || ($freeFrom !== null && $base >= (float) $freeFrom)) {
                $shipping = 0.0;
            }
        }

        return [
            'items'     => $items,
            'subtotal'  => $subtotal,
            'discount'  => $discount,
            'shipping'  => round($shipping, 2),
            'total'     => round(max(0, $subtotal - $discount) + $shipping, 2),
            'coupon'    => $coupon,
            'rate'      => $rate,
            'count'     => self::count(),
        ];
    }
}
