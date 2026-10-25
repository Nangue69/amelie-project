<?php

use App\Core\Cart;

/** @var array $totals */
/** @var float $freeFrom */

$items    = $totals['items'];
$coupon   = $totals['coupon'];
$subtotal = (float) $totals['subtotal'];
$missing  = $freeFrom > 0 ? max(0, $freeFrom - $subtotal) : 0;
$progress = $freeFrom > 0 ? min(100, ($subtotal / $freeFrom) * 100) : 0;
?>
<div class="page-head">
    <div class="wrap">
        <h1><?= e(t('cart.title')) ?></h1>
        <?php if ($items !== []): ?>
            <p><?= (int) $totals['count'] ?> <?= e($totals['count'] === 1 ? t('cart.item') : t('cart.items')) ?></p>
        <?php endif; ?>
    </div>
</div>

<div class="wrap">
    <?php if ($items === []): ?>
        <div class="empty-state">
            <p><?= e(t('cart.empty')) ?></p>
            <a class="btn" href="<?= e(lang_url('shop')) ?>"><?= e(t('cart.empty_cta')) ?></a>
        </div>
    <?php else: ?>
        <div class="cart-layout" data-cart-page>
            <div>
                <?php foreach ($items as $item): ?>
                    <article class="cart-line">
                        <a class="cart-line__media" href="<?= e(lang_url('product/' . $item['slug'])) ?>" tabindex="-1" aria-hidden="true">
                            <img src="<?= e(img($item['image'])) ?>" alt="" loading="lazy" width="200" height="266">
                        </a>

                        <div>
                            <h2 class="cart-line__name">
                                <a href="<?= e(lang_url('product/' . $item['slug'])) ?>"><?= e($item['name']) ?></a>
                            </h2>
                            <p class="cart-line__size"><?= e($item['size_label']) ?></p>
                            <p class="cart-line__size"><?= money($item['unit_price']) ?></p>

                            <div class="cart-line__actions">
                                <div class="qty">
                                    <label class="visually-hidden" for="qty-<?= (int) $item['variant_id'] ?>">
                                        <?= e(t('product.quantity')) ?>
                                    </label>
                                    <input type="number" id="qty-<?= (int) $item['variant_id'] ?>"
                                           value="<?= (int) $item['quantity'] ?>" min="1" max="<?= (int) $item['stock'] ?>"
                                           data-line-qty data-variant="<?= (int) $item['variant_id'] ?>">
                                </div>

                                <form action="<?= e(lang_url('cart/remove')) ?>" method="post">
                                    <?= csrf_field() ?>
                                    <input type="hidden" name="variant_id" value="<?= (int) $item['variant_id'] ?>">
                                    <button class="cart-line__remove" type="submit"><?= e(t('cart.remove')) ?></button>
                                </form>
                            </div>
                        </div>

                        <p class="cart-line__total"><?= money($item['line_total']) ?></p>
                    </article>
                <?php endforeach; ?>

                <p style="margin-top:2.4rem;">
                    <a class="link-underline" href="<?= e(lang_url('shop')) ?>"><?= e(t('cart.continue')) ?></a>
                </p>
            </div>

            <aside class="summary">
                <h2><?= e(t('checkout.summary')) ?></h2>

                <?php if ($freeFrom > 0): ?>
                    <div class="free-ship-bar">
                        <?php if ($missing > 0): ?>
                            <?= e(t('cart.free_left', [money($missing)])) ?>
                        <?php else: ?>
                            <?= e(t('cart.free_ok')) ?>
                        <?php endif; ?>
                        <div class="free-ship-bar__track">
                            <div class="free-ship-bar__fill" style="width: <?= (float) $progress ?>%;"></div>
                        </div>
                    </div>
                <?php endif; ?>

                <?php if ($coupon === null): ?>
                    <form class="coupon-form" action="<?= e(lang_url('cart/coupon')) ?>" method="post">
                        <?= csrf_field() ?>
                        <label class="visually-hidden" for="coupon"><?= e(t('cart.coupon')) ?></label>
                        <input class="input" type="text" id="coupon" name="code"
                               placeholder="<?= e(t('cart.coupon_placeholder')) ?>" autocomplete="off">
                        <button class="btn btn--sm btn--ghost" type="submit"><?= e(t('cart.coupon_apply')) ?></button>
                    </form>
                <?php else: ?>
                    <div class="coupon-applied">
                        <span><?= e(t('cart.coupon')) ?>: <strong><?= e((string) $coupon['code']) ?></strong></span>
                        <form action="<?= e(lang_url('cart/coupon')) ?>" method="post">
                            <?= csrf_field() ?>
                            <input type="hidden" name="action" value="remove">
                            <button type="submit"><?= e(t('cart.remove')) ?></button>
                        </form>
                    </div>
                <?php endif; ?>

                <div class="summary__row">
                    <span><?= e(t('cart.subtotal')) ?></span>
                    <span><?= money($totals['subtotal']) ?></span>
                </div>

                <?php if ((float) $totals['discount'] > 0): ?>
                    <div class="summary__row summary__row--discount">
                        <span><?= e(t('cart.discount')) ?></span>
                        <span>&minus; <?= money($totals['discount']) ?></span>
                    </div>
                <?php endif; ?>

                <div class="summary__row">
                    <span><?= e(t('cart.shipping')) ?></span>
                    <span><?= e(t('cart.shipping_calc')) ?></span>
                </div>

                <div class="summary__row summary__row--total">
                    <span><?= e(t('cart.total')) ?></span>
                    <span><?= money((float) $totals['subtotal'] - (float) $totals['discount']) ?></span>
                </div>

                <a class="btn btn--block" style="margin-top:2.4rem;"
                   href="<?= e(lang_url('checkout')) ?>"><?= e(t('cart.checkout')) ?></a>
            </aside>
        </div>
    <?php endif; ?>
</div>
