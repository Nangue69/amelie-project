<?php

use App\Core\Settings;
use App\Core\View;
use App\Models\Order;
use App\Models\Shipping;

/** @var array $order */
/** @var array $items */

View::share('pageTitle', 'Pedido ' . $order['order_number']);
?>
<p class="page-intro">
    <a href="<?= e(admin_url('orders')) ?>" style="color:var(--adm-brand);">&larr; Volver a pedidos</a>
    &nbsp;·&nbsp; Recibido el <?= e(format_date($order['created_at'], 'd/m/Y \a \l\a\s H:i')) ?>
</p>

<div class="grid-2">
    <section class="card">
        <div class="card__head"><div><h2>Qué ha comprado</h2></div></div>

        <div class="table-wrap">
            <table class="table">
                <thead>
                    <tr><th></th><th>Pieza</th><th class="num">Unidades</th><th class="num">Importe</th></tr>
                </thead>
                <tbody>
                    <?php foreach ($items as $item): ?>
                        <tr>
                            <td><img class="table__media" src="<?= e(img((string) $item['image'])) ?>" alt="" loading="lazy"></td>
                            <td>
                                <strong><?= e((string) $item['product_name']) ?></strong><br>
                                <small style="color:var(--adm-soft);"><?= e((string) $item['variant_label']) ?></small><br>
                                <small style="color:var(--adm-faint);"><?= e((string) $item['sku']) ?></small>
                            </td>
                            <td class="num"><?= (int) $item['quantity'] ?></td>
                            <td class="num"><?= money($item['line_total']) ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>

        <div style="margin-top:1.6rem;padding-top:1.6rem;border-top:1px solid var(--adm-line);">
            <div class="summary__row"><span>Subtotal</span><span><?= money($order['subtotal']) ?></span></div>

            <?php if ((float) $order['discount_total'] > 0): ?>
                <div class="summary__row" style="color:var(--adm-ok);">
                    <span>Descuento<?= $order['coupon_code'] !== '' ? ' (' . e((string) $order['coupon_code']) . ')' : '' ?></span>
                    <span>&minus; <?= money($order['discount_total']) ?></span>
                </div>
            <?php endif; ?>

            <div class="summary__row"><span>Envío</span><span><?= money($order['shipping_cost']) ?></span></div>
            <div class="summary__row summary__row--total"><span>Total</span><span><?= money($order['total']) ?></span></div>
        </div>
    </section>

    <div>
        <section class="card">
            <div class="card__head"><div><h2>Gestión</h2><p>Cambia el estado según avanza el pedido.</p></div></div>

            <form action="<?= e(admin_url('orders/' . $order['id'])) ?>" method="post">
                <?= csrf_field() ?>

                <div class="adm-field">
                    <label for="status">Estado del pedido</label>
                    <select class="adm-select" id="status" name="status">
                        <?php foreach (Order::STATUSES as $value): ?>
                            <option value="<?= e($value) ?>" <?= $order['status'] === $value ? 'selected' : '' ?>>
                                <?= e(Order::statusLabel($value)) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                    <p class="hint">Al marcarlo como «Enviado» se avisa por correo a la clienta.</p>
                </div>

                <div class="adm-field">
                    <label for="tracking_code">Número de seguimiento</label>
                    <input class="adm-input" type="text" id="tracking_code" name="tracking_code"
                           value="<?= e((string) $order['tracking_code']) ?>">
                </div>

                <div class="adm-field">
                    <label for="admin_notes">Notas internas</label>
                    <textarea class="adm-textarea" id="admin_notes" name="admin_notes"
                              style="min-height:9rem;"><?= e((string) $order['admin_notes']) ?></textarea>
                    <p class="hint">Sólo las ves tú. La clienta no las recibe.</p>
                </div>

                <button class="abtn abtn--block" type="submit">Guardar</button>
            </form>
        </section>

        <section class="card" style="margin-top:2.4rem;">
            <div class="card__head"><div><h2>Clienta y envío</h2></div></div>

            <p style="margin:0 0 1.6rem;line-height:1.8;">
                <strong><?= e((string) $order['customer_name']) ?></strong><br>
                <a href="mailto:<?= e((string) $order['customer_email']) ?>" style="color:var(--adm-brand);">
                    <?= e((string) $order['customer_email']) ?>
                </a><br>
                <?php if ($order['customer_phone'] !== ''): ?>
                    <?= e((string) $order['customer_phone']) ?><br>
                <?php endif; ?>
            </p>

            <p style="margin:0 0 1.6rem;line-height:1.8;color:var(--adm-soft);">
                <?= e((string) $order['ship_address']) ?><?= $order['ship_address2'] !== '' ? ', ' . e((string) $order['ship_address2']) : '' ?><br>
                <?= e((string) $order['ship_postal']) ?> <?= e((string) $order['ship_city']) ?><br>
                <?= $order['ship_province'] !== '' ? e((string) $order['ship_province']) . '<br>' : '' ?>
                <?= e(Shipping::countryName((string) $order['ship_country'])) ?>
            </p>

            <dl style="margin:0;font-size:1.35rem;">
                <dt style="color:var(--adm-soft);">Forma de pago</dt>
                <dd style="margin:.2rem 0 1.2rem;">
                    <?= $order['payment_method'] === 'card' ? 'Tarjeta' : 'Transferencia bancaria' ?>
                    <?php if ($order['payment_method'] === 'bank_transfer'): ?>
                        <br><small style="color:var(--adm-faint);"><?= e(Settings::get('bank_iban')) ?></small>
                    <?php endif; ?>
                </dd>

                <dt style="color:var(--adm-soft);">Idioma de la clienta</dt>
                <dd style="margin:.2rem 0 1.2rem;"><?= e(strtoupper((string) $order['lang'])) ?></dd>
            </dl>

            <?php if (!empty($order['notes'])): ?>
                <div class="alert" style="margin-top:1.6rem;">
                    <div>
                        <strong>Comentario de la clienta</strong><br>
                        <?= nl2br(e((string) $order['notes'])) ?>
                    </div>
                </div>
            <?php endif; ?>
        </section>
    </div>
</div>
