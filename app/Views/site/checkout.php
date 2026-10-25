<?php

use App\Core\Flash;

/** @var array $totals */
/** @var array $countries */
/** @var string $country */
/** @var array $errors */
/** @var array $methods */

$items = $totals['items'];
$rate  = $totals['rate'];

$invalid = static fn (string $field): string => isset($errors[$field]) ? ' aria-invalid="true"' : '';
?>
<div class="page-head">
    <div class="wrap">
        <h1><?= e(t('checkout.title')) ?></h1>
        <p><a class="link-underline" href="<?= e(lang_url('cart')) ?>"><?= e(t('checkout.back')) ?></a></p>
    </div>
</div>

<div class="wrap">
    <form class="checkout-layout" action="<?= e(lang_url('checkout')) ?>" method="post" novalidate>
        <?= csrf_field() ?>

        <div>
            <section class="checkout-block">
                <h2><span class="checkout-block__num">1</span><?= e(t('checkout.contact')) ?></h2>

                <div class="form-grid">
                    <div class="field field--full">
                        <label class="field__label" for="name"><?= e(t('form.name')) ?></label>
                        <input class="input" type="text" id="name" name="name" required autocomplete="name"
                               value="<?= e(Flash::old('name')) ?>"<?= $invalid('name') ?>>
                    </div>

                    <div class="field">
                        <label class="field__label" for="email"><?= e(t('form.email')) ?></label>
                        <input class="input" type="email" id="email" name="email" required autocomplete="email"
                               value="<?= e(Flash::old('email')) ?>"<?= $invalid('email') ?>>
                    </div>

                    <div class="field">
                        <label class="field__label" for="phone">
                            <?= e(t('form.phone')) ?> <span style="text-transform:none;letter-spacing:0;">(<?= e(t('form.optional')) ?>)</span>
                        </label>
                        <input class="input" type="tel" id="phone" name="phone" autocomplete="tel"
                               value="<?= e(Flash::old('phone')) ?>">
                    </div>
                </div>
            </section>

            <section class="checkout-block">
                <h2><span class="checkout-block__num">2</span><?= e(t('checkout.shipping')) ?></h2>

                <div class="form-grid">
                    <div class="field field--full">
                        <label class="field__label" for="address"><?= e(t('form.address')) ?></label>
                        <input class="input" type="text" id="address" name="address" required autocomplete="address-line1"
                               value="<?= e(Flash::old('address')) ?>"<?= $invalid('address') ?>>
                    </div>

                    <div class="field field--full">
                        <label class="field__label" for="address2">
                            <?= e(t('form.address2')) ?> <span style="text-transform:none;letter-spacing:0;">(<?= e(t('form.optional')) ?>)</span>
                        </label>
                        <input class="input" type="text" id="address2" name="address2" autocomplete="address-line2"
                               value="<?= e(Flash::old('address2')) ?>">
                    </div>

                    <div class="field">
                        <label class="field__label" for="postal"><?= e(t('form.postal')) ?></label>
                        <input class="input" type="text" id="postal" name="postal" required autocomplete="postal-code"
                               value="<?= e(Flash::old('postal')) ?>"<?= $invalid('postal') ?>>
                    </div>

                    <div class="field">
                        <label class="field__label" for="city"><?= e(t('form.city')) ?></label>
                        <input class="input" type="text" id="city" name="city" required autocomplete="address-level2"
                               value="<?= e(Flash::old('city')) ?>"<?= $invalid('city') ?>>
                    </div>

                    <div class="field">
                        <label class="field__label" for="province"><?= e(t('form.province')) ?></label>
                        <input class="input" type="text" id="province" name="province" autocomplete="address-level1"
                               value="<?= e(Flash::old('province')) ?>">
                    </div>

                    <div class="field">
                        <label class="field__label" for="country"><?= e(t('form.country')) ?></label>
                        <select class="select" id="country" name="country" required data-country autocomplete="country">
                            <?php foreach ($countries as $code => $name): ?>
                                <option value="<?= e($code) ?>" <?= $country === $code ? 'selected' : '' ?>><?= e($name) ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                </div>
            </section>

            <section class="checkout-block">
                <h2><span class="checkout-block__num">3</span><?= e(t('checkout.payment')) ?></h2>

                <?php $first = true; foreach ($methods as $key => $method): ?>
                    <label class="pay-option">
                        <span class="pay-option__head">
                            <input type="radio" name="payment_method" value="<?= e($key) ?>" <?= $first ? 'checked' : '' ?> required>
                            <span><?= e($method['label']) ?></span>
                        </span>
                        <span class="pay-option__desc"><?= e($method['description']) ?></span>
                    </label>
                <?php $first = false; endforeach; ?>

                <div class="field" style="margin-top:2.4rem;">
                    <label class="field__label" for="notes">
                        <?= e(t('checkout.notes')) ?> <span style="text-transform:none;letter-spacing:0;">(<?= e(t('form.optional')) ?>)</span>
                    </label>
                    <textarea class="textarea" id="notes" name="notes" rows="3" style="min-height:9rem;"><?= e(Flash::old('notes')) ?></textarea>
                </div>

                <label class="checkbox">
                    <input type="checkbox" name="terms" value="1" required>
                    <span>
                        <?= e(t('checkout.terms')) ?>
                        <a href="<?= e(lang_url('terms')) ?>" target="_blank" rel="noopener"
                           style="text-decoration:underline;"><?= e(t('footer.legal')) ?></a>
                    </span>
                </label>
            </section>
        </div>

        <aside class="summary">
            <h2><?= e(t('checkout.summary')) ?></h2>

            <?php foreach ($items as $item): ?>
                <div class="mini-line">
                    <div class="mini-line__media">
                        <img src="<?= e(img($item['image'])) ?>" alt="" loading="lazy" width="120" height="160">
                    </div>
                    <div>
                        <strong><?= e($item['name']) ?></strong>
                        <span><?= e($item['size_label']) ?> &middot; <?= (int) $item['quantity'] ?></span>
                    </div>
                    <span><?= money($item['line_total']) ?></span>
                </div>
            <?php endforeach; ?>

            <div class="summary__row" style="margin-top:1.6rem;">
                <span><?= e(t('cart.subtotal')) ?></span>
                <span><?= money($totals['subtotal']) ?></span>
            </div>

            <?php if ((float) $totals['discount'] > 0): ?>
                <div class="summary__row summary__row--discount">
                    <span><?= e(t('cart.discount')) ?><?= $totals['coupon'] ? ' (' . e((string) $totals['coupon']['code']) . ')' : '' ?></span>
                    <span>&minus; <?= money($totals['discount']) ?></span>
                </div>
            <?php endif; ?>

            <div class="summary__row">
                <span>
                    <?= e(t('cart.shipping')) ?>
                    <?php if ($rate !== null): ?>
                        <br><small style="color:var(--ink-faint);"><?= e((string) $rate['zone_name']) ?></small>
                    <?php endif; ?>
                </span>
                <span><?= (float) $totals['shipping'] > 0 ? money($totals['shipping']) : e(t('cart.free_ok')) ?></span>
            </div>

            <div class="summary__row summary__row--total">
                <span><?= e(t('cart.total')) ?></span>
                <span><?= money($totals['total']) ?></span>
            </div>

            <button class="btn btn--block" type="submit" style="margin-top:2.4rem;">
                <?= e(t('checkout.place_order')) ?>
            </button>

            <?php if ($rate !== null && $rate['delivery_days'] !== ''): ?>
                <p class="summary__note"><?= e((string) $rate['delivery_days']) ?> <?= e(t('cart.shipping')) ?></p>
            <?php endif; ?>
        </aside>
    </form>
</div>
