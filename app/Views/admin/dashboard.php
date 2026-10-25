<?php

use App\Core\View;
use App\Models\Order;

/** @var array $stats */
/** @var array $recentOrders */
/** @var array $lowStock */
/** @var array $activity */
/** @var bool $comingSoon */

View::share('pageTitle', 'Escritorio');
?>
<?php if ($comingSoon): ?>
    <div class="alert alert--warn">
        <div>
            <strong>La web está en modo «Coming Soon».</strong><br>
            Las visitantes sólo ven la página de presentación y el formulario de suscripción: no se puede comprar.
            Puedes desactivarlo en <a href="<?= e(admin_url('settings')) ?>" style="text-decoration:underline;">Ajustes</a>.
        </div>
    </div>
<?php endif; ?>

<p class="page-intro">
    Un vistazo rápido a cómo va la tienda. Lo que necesita tu atención aparece destacado.
</p>

<div class="kpis">
    <div class="kpi">
        <p class="kpi__label">Ventas este mes</p>
        <p class="kpi__value"><?= money($stats['revenueMonth']) ?></p>
        <p class="kpi__note">Total acumulado: <?= money($stats['revenueTotal']) ?></p>
    </div>

    <div class="kpi <?= $stats['ordersPending'] > 0 ? 'kpi--alert' : '' ?>">
        <p class="kpi__label">Pedidos por preparar</p>
        <p class="kpi__value"><?= (int) $stats['ordersPending'] ?></p>
        <p class="kpi__note"><?= (int) $stats['ordersTotal'] ?> pedidos en total</p>
    </div>

    <div class="kpi">
        <p class="kpi__label">Productos a la venta</p>
        <p class="kpi__value"><?= (int) $stats['products'] ?></p>
        <p class="kpi__note"><?= (int) $stats['drafts'] ?> sin publicar</p>
    </div>

    <div class="kpi <?= $stats['messages'] > 0 ? 'kpi--alert' : '' ?>">
        <p class="kpi__label">Mensajes sin leer</p>
        <p class="kpi__value"><?= (int) $stats['messages'] ?></p>
        <p class="kpi__note"><?= (int) $stats['subscribers'] ?> suscriptoras activas</p>
    </div>
</div>

<div class="grid-2">
    <section class="card">
        <div class="card__head">
            <div>
                <h2>Últimos pedidos</h2>
                <p>Lo más reciente que han comprado.</p>
            </div>
            <div class="spacer"></div>
            <a class="abtn abtn--ghost abtn--sm" href="<?= e(admin_url('orders')) ?>">Ver todos</a>
        </div>

        <?php if ($recentOrders === []): ?>
            <div class="empty">
                <h3>Todavía no hay pedidos</h3>
                <p>Aquí aparecerán en cuanto llegue el primero.</p>
            </div>
        <?php else: ?>
            <div class="table-wrap">
                <table class="table">
                    <thead>
                        <tr>
                            <th>Pedido</th><th>Clienta</th><th class="num">Importe</th><th>Estado</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($recentOrders as $order): ?>
                            <tr>
                                <td>
                                    <a href="<?= e(admin_url('orders/' . $order['id'])) ?>" style="color:var(--adm-brand);">
                                        <?= e((string) $order['order_number']) ?>
                                    </a><br>
                                    <small style="color:var(--adm-faint);"><?= e(format_date($order['created_at'], 'd/m/Y H:i')) ?></small>
                                </td>
                                <td><?= e((string) $order['customer_name']) ?></td>
                                <td class="num"><?= money($order['total']) ?></td>
                                <td>
                                    <span class="tag <?= $order['status'] === 'pending' ? 'tag--warn' : 'tag--ok' ?>">
                                        <?= e(Order::statusLabel((string) $order['status'])) ?>
                                    </span>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        <?php endif; ?>
    </section>

    <section class="card">
        <div class="card__head">
            <div>
                <h2>Tallas que se están agotando</h2>
                <p>Conviene reponer antes de quedarse sin existencias.</p>
            </div>
            <div class="spacer"></div>
            <a class="abtn abtn--ghost abtn--sm" href="<?= e(admin_url('stock')) ?>">Gestionar</a>
        </div>

        <?php if ($lowStock === []): ?>
            <div class="empty">
                <h3>Todo en orden</h3>
                <p>No hay ninguna talla por debajo del mínimo.</p>
            </div>
        <?php else: ?>
            <div class="table-wrap">
                <table class="table">
                    <thead><tr><th>Producto</th><th>Talla</th><th class="num">Quedan</th></tr></thead>
                    <tbody>
                        <?php foreach ($lowStock as $row): ?>
                            <tr>
                                <td><?= e((string) ($row['name'] ?? $row['slug'])) ?></td>
                                <td>
                                    <?= $row['size_mode'] === 'bra_brief'
                                        ? e($row['size_bra'] . ' / ' . $row['size_brief'])
                                        : e((string) $row['size']) ?>
                                </td>
                                <td class="num">
                                    <span class="tag <?= (int) $row['stock'] === 0 ? 'tag--danger' : 'tag--warn' ?>">
                                        <?= (int) $row['stock'] ?>
                                    </span>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        <?php endif; ?>
    </section>
</div>

<section class="card">
    <div class="card__head">
        <div>
            <h2>Últimos cambios</h2>
            <p>Quién ha tocado qué, y cuándo.</p>
        </div>
        <div class="spacer"></div>
        <a class="abtn abtn--ghost abtn--sm" href="<?= e(admin_url('activity')) ?>">Ver todo</a>
    </div>

    <?php if ($activity === []): ?>
        <div class="empty"><p>Sin movimientos todavía.</p></div>
    <?php else: ?>
        <ul class="log">
            <?php foreach ($activity as $entry): ?>
                <li>
                    <strong><?= e((string) $entry['user_name']) ?></strong>
                    <span><?= e(trim($entry['action'] . ' ' . $entry['entity'])) ?></span>
                    <?php if ($entry['description'] !== ''): ?>
                        <span style="color:var(--adm-soft);"><?= e((string) $entry['description']) ?></span>
                    <?php endif; ?>
                    <time datetime="<?= e(format_date($entry['created_at'], 'Y-m-d\TH:i')) ?>">
                        <?= e(format_date($entry['created_at'], 'd/m/Y H:i')) ?>
                    </time>
                </li>
            <?php endforeach; ?>
        </ul>
    <?php endif; ?>
</section>
