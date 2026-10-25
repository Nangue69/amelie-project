<?php

use App\Core\Settings;
use App\Models\Shipping;

/** @var array $order */
/** @var array $items */
?>
<div class="wrap confirm">
    <div class="confirm__mark" aria-hidden="true">
        <svg viewBox="0 0 24 24"><path d="M4 12.5l5 5L20 7"/></svg>
    </div>

    <h1><?= e(t('checkout.success_title')) ?></h1>
    <p class="lede"><?= e(t('checkout.success_text')) ?></p>

    <p class="confirm__number">
        <span class="visually-hidden"><?= e(t('checkout.success_number')) ?>:</span>
        <?= e((string) $order['order_number']) ?>
    </p>

    <?php if ($order['payment_method'] === 'bank_transfer'): ?>
        <div class="confirm__panel">
            <h2 style="font-size:1.8rem;"><?= e(t('checkout.bank')) ?></h2>
            <p><?= e(t('checkout.bank_instructions')) ?></p>
            <dl style="margin:0;">
                <dt style="font-size:1.1rem;letter-spacing:.18em;text-transform:uppercase;color:var(--ink-soft);">IBAN</dt>
                <dd style="margin:.2rem 0 1.2rem;font-size:1.6rem;"><?= e(Settings::get('bank_iban')) ?></dd>
                <dt style="font-size:1.1rem;letter-spacing:.18em;text-transform:uppercase;color:var(--ink-soft);"><?= e(t('form.name')) ?></dt>
                <dd style="margin:.2rem 0 1.2rem;font-size:1.6rem;"><?= e(Settings::get('bank_holder')) ?></dd>
                <dt style="font-size:1.1rem;letter-spacing:.18em;text-transform:uppercase;color:var(--ink-soft);"><?= e(t('checkout.success_number')) ?></dt>
                <dd style="margin:.2rem 0;font-size:1.6rem;"><?= e((string) $order['order_number']) ?></dd>
            </dl>
        </div>
    <?php endif; ?>

    <div class="confirm__panel">
        <h2 style="font-size:1.8rem;"><?= e(t('checkout.summary')) ?></h2>

        <?php foreach ($items as $item): ?>
            <div class="mini-line">
                <div class="mini-line__media">
                    <img src="<?= e(img((string) $item['image'])) ?>" alt="" loading="lazy" width="120" height="160">
                </div>
                <div>
                    <strong><?= e((string) $item['product_name']) ?></strong>
                    <span><?= e((string) $item['variant_label']) ?> &middot; <?= (int) $item['quantity'] ?></span>
                </div>
                <span><?= money($item['line_total']) ?></span>
            </div>
        <?php endforeach; ?>

        <div class="summary__row" style="margin-top:1.6rem;">
            <span><?= e(t('cart.subtotal')) ?></span><span><?= money($order['subtotal']) ?></span>
        </div>
        <?php if ((float) $order['discount_total'] > 0): ?>
            <div class="summary__row summary__row--discount">
                <span><?= e(t('cart.discount')) ?></span><span>&minus; <?= money($order['discount_total']) ?></span>
            </div>
        <?php endif; ?>
        <div class="summary__row">
            <span><?= e(t('cart.shipping')) ?></span><span><?= money($order['shipping_cost']) ?></span>
        </div>
        <div class="summary__row summary__row--total">
            <span><?= e(t('cart.total')) ?></span><span><?= money($order['total']) ?></span>
        </div>
    </div>

    <div class="confirm__panel">
        <h2 style="font-size:1.8rem;"><?= e(t('checkout.shipping')) ?></h2>
        <p style="margin:0;">
            <?= e((string) $order['customer_name']) ?><br>
            <?= e((string) $order['ship_address']) ?><?= $order['ship_address2'] !== '' ? ', ' . e((string) $order['ship_address2']) : '' ?><br>
            <?= e((string) $order['ship_postal']) ?> <?= e((string) $order['ship_city']) ?><br>
            <?= e(Shipping::countryName((string) $order['ship_country'])) ?>
        </p>
    </div>

    <a class="btn" href="<?= e(lang_url('shop')) ?>"><?= e(t('checkout.success_cta')) ?></a>
</div>
