<?php

use App\Core\View;
use App\Models\Order;
use App\Models\Shipping;

/** @var array $orders */
/** @var string $status */
/** @var string $search */
/** @var array $counts */

View::share('pageTitle', 'Pedidos');

$tagFor = static fn (string $value): string => match ($value) {
    'pending'              => 'tag--warn',
    'paid', 'processing'   => 'tag--info',
    'shipped', 'delivered' => 'tag--ok',
    'cancelled', 'refunded'=> 'tag--danger',
    default                => 'tag--muted',
};
?>
<p class="page-intro">
    Cada compra que entra. Pulsa sobre un pedido para ver el detalle, cambiar su estado y
    añadir el número de seguimiento del envío.
</p>

<section class="card">
    <div class="card__head">
        <div class="filters">
            <a href="<?= e(admin_url('orders')) ?>" <?= $status === '' ? 'aria-current="page"' : '' ?>>
                Todos (<?= array_sum($counts) ?>)
            </a>
            <?php foreach (Order::STATUSES as $value): ?>
                <?php if ((int) $counts[$value] === 0 && $status !== $value) { continue; } ?>
                <a href="<?= e(admin_url('orders?status=' . $value)) ?>"
                   <?= $status === $value ? 'aria-current="page"' : '' ?>>
                    <?= e(Order::statusLabel($value)) ?> (<?= (int) $counts[$value] ?>)
                </a>
            <?php endforeach; ?>
        </div>

        <div class="spacer"></div>

        <form method="get" action="<?= e(admin_url('orders')) ?>" style="display:flex;gap:.6rem;">
            <?php if ($status !== ''): ?>
                <input type="hidden" name="status" value="<?= e($status) ?>">
            <?php endif; ?>
            <label class="visually-hidden" for="q">Buscar pedido</label>
            <input class="adm-input adm-input--sm" type="search" id="q" name="q" value="<?= e($search) ?>"
                   placeholder="Número, nombre o correo" style="max-width:24rem;">
            <button class="abtn abtn--ghost abtn--sm" type="submit">Buscar</button>
        </form>
    </div>

    <?php if ($orders === []): ?>
        <div class="empty">
            <h3>No hay pedidos que mostrar</h3>
            <p><?= $search !== '' || $status !== '' ? 'Prueba a quitar el filtro.' : 'Aquí aparecerán en cuanto llegue el primero.' ?></p>
        </div>
    <?php else: ?>
        <div class="table-wrap">
            <table class="table">
                <thead>
                    <tr>
                        <th>Pedido</th><th>Clienta</th><th>Destino</th>
                        <th class="num">Importe</th><th>Pago</th><th>Estado</th><th class="actions"></th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($orders as $order): ?>
                        <tr>
                            <td>
                                <a href="<?= e(admin_url('orders/' . $order['id'])) ?>" style="color:var(--adm-brand);">
                                    <strong><?= e((string) $order['order_number']) ?></strong>
                                </a><br>
                                <small style="color:var(--adm-faint);"><?= e(format_date($order['created_at'], 'd/m/Y H:i')) ?></small>
                            </td>
                            <td>
                                <?= e((string) $order['customer_name']) ?><br>
                                <small style="color:var(--adm-faint);"><?= e((string) $order['customer_email']) ?></small>
                            </td>
                            <td><?= e(Shipping::countryName((string) $order['ship_country'])) ?></td>
                            <td class="num"><?= money($order['total']) ?></td>
                            <td>
                                <span class="tag <?= $order['payment_status'] === 'paid' ? 'tag--ok' : 'tag--warn' ?>">
                                    <?= $order['payment_status'] === 'paid' ? 'Cobrado' : 'Pendiente' ?>
                                </span>
                            </td>
                            <td>
                                <span class="tag <?= $tagFor((string) $order['status']) ?>">
                                    <?= e(Order::statusLabel((string) $order['status'])) ?>
                                </span>
                            </td>
                            <td class="actions">
                                <a class="abtn abtn--ghost abtn--sm" href="<?= e(admin_url('orders/' . $order['id'])) ?>">Abrir</a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    <?php endif; ?>
</section>
