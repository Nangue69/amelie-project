<?php

declare(strict_types=1);

namespace App\Models;

use App\Core\Database;

final class Order
{
    public const STATUSES = ['pending', 'paid', 'processing', 'shipped', 'delivered', 'cancelled', 'refunded'];

    public const STATUS_LABELS = [
        'pending'    => 'Pendiente de pago',
        'paid'       => 'Pagado',
        'processing' => 'En preparación',
        'shipped'    => 'Enviado',
        'delivered'  => 'Entregado',
        'cancelled'  => 'Cancelado',
        'refunded'   => 'Reembolsado',
    ];

    public static function nextNumber(): string
    {
        $prefix = 'AM' . date('y');
        $last   = Database::value(
            'SELECT `order_number` FROM `orders`
              WHERE `order_number` LIKE :p
              ORDER BY `id` DESC LIMIT 1',
            ['p' => $prefix . '%']
        );

        $sequence = is_string($last) ? ((int) substr($last, strlen($prefix)) + 1) : 1;

        return $prefix . str_pad((string) $sequence, 5, '0', STR_PAD_LEFT);
    }

    /**
     * Crea el pedido y descuenta el stock dentro de una transaccion.
     */
    public static function create(array $order, array $items): int
    {
        return (int) Database::transaction(static function () use ($order, $items): int {
            $orderId = Database::insert('orders', $order);

            foreach ($items as $item) {
                Database::insert('order_items', [
                    'order_id'      => $orderId,
                    'product_id'    => $item['product_id'],
                    'variant_id'    => $item['variant_id'],
                    'product_name'  => $item['name'],
                    'variant_label' => $item['size_label'],
                    'sku'           => $item['sku'],
                    'image'         => $item['image'],
                    'unit_price'    => $item['unit_price'],
                    'quantity'      => $item['quantity'],
                    'line_total'    => $item['line_total'],
                ]);

                Database::query(
                    'UPDATE `product_variants`
                        SET `stock` = GREATEST(0, `stock` - :q)
                      WHERE `id` = :id',
                    ['q' => $item['quantity'], 'id' => $item['variant_id']]
                );
            }

            if (($order['coupon_code'] ?? '') !== '') {
                Database::query(
                    'UPDATE `coupons` SET `used_count` = `used_count` + 1 WHERE `code` = :c',
                    ['c' => $order['coupon_code']]
                );
            }

            return $orderId;
        });
    }

    public static function findByNumber(string $number): ?array
    {
        return Database::one('SELECT * FROM `orders` WHERE `order_number` = :n', ['n' => $number]);
    }

    public static function find(int $id): ?array
    {
        return Database::one('SELECT * FROM `orders` WHERE `id` = :id', ['id' => $id]);
    }

    public static function items(int $orderId): array
    {
        return Database::all('SELECT * FROM `order_items` WHERE `order_id` = :id ORDER BY `id`', ['id' => $orderId]);
    }

    public static function all(string $status = '', string $search = ''): array
    {
        $where  = '1 = 1';
        $params = [];

        if ($status !== '' && in_array($status, self::STATUSES, true)) {
            $where .= ' AND `status` = :status';
            $params['status'] = $status;
        }

        if ($search !== '') {
            $where .= ' AND (`order_number` LIKE :a OR `customer_name` LIKE :b OR `customer_email` LIKE :c)';
            $like = '%' . $search . '%';
            $params += ['a' => $like, 'b' => $like, 'c' => $like];
        }

        return Database::all(
            "SELECT * FROM `orders` WHERE {$where} ORDER BY `created_at` DESC LIMIT 200",
            $params
        );
    }

    public static function updateStatus(int $orderId, string $status): void
    {
        if (!in_array($status, self::STATUSES, true)) {
            return;
        }

        $data = ['status' => $status];

        if (in_array($status, ['paid', 'processing', 'shipped', 'delivered'], true)) {
            $data['payment_status'] = 'paid';
        } elseif ($status === 'refunded') {
            $data['payment_status'] = 'refunded';
        }

        Database::update('orders', $data, '`id` = :id', ['id' => $orderId]);
    }

    public static function statusLabel(string $status): string
    {
        return self::STATUS_LABELS[$status] ?? $status;
    }

    // ------------------------------------------------------------------
    // Indicadores del escritorio
    // ------------------------------------------------------------------

    public static function countByStatus(string $status): int
    {
        return (int) Database::value(
            'SELECT COUNT(*) FROM `orders` WHERE `status` = :s',
            ['s' => $status],
            0
        );
    }

    public static function totalRevenue(): float
    {
        return (float) Database::value(
            'SELECT COALESCE(SUM(`total`), 0) FROM `orders`
              WHERE `payment_status` = "paid" AND `status` <> "refunded"',
            [],
            0
        );
    }

    public static function revenueThisMonth(): float
    {
        return (float) Database::value(
            'SELECT COALESCE(SUM(`total`), 0) FROM `orders`
              WHERE `payment_status` = "paid" AND `status` <> "refunded"
                AND YEAR(`created_at`) = YEAR(CURDATE())
                AND MONTH(`created_at`) = MONTH(CURDATE())',
            [],
            0
        );
    }

    public static function recent(int $limit = 8): array
    {
        $limit = max(1, min($limit, 50));

        return Database::all(
            'SELECT `id`, `order_number`, `customer_name`, `total`, `status`, `created_at`
               FROM `orders` ORDER BY `created_at` DESC LIMIT ' . $limit
        );
    }

    public static function countAll(): int
    {
        return (int) Database::value('SELECT COUNT(*) FROM `orders`', [], 0);
    }
}
