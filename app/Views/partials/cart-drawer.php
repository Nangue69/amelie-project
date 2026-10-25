<div class="drawer drawer--right" id="cart-drawer" role="dialog" aria-modal="true"
     aria-label="<?= e(t('cart.title')) ?>" hidden>
    <button type="button" class="drawer__backdrop" data-close aria-label="<?= e(t('common.close')) ?>"></button>

    <div class="drawer__panel">
        <div class="drawer__head">
            <p class="drawer__title"><?= e(t('cart.title')) ?></p>
            <button type="button" class="modal__close" data-close style="position:static;"
                    aria-label="<?= e(t('common.close')) ?>">&times;</button>
        </div>

        <div data-cart-lines></div>

        <div data-cart-footer hidden>
            <div class="summary__row summary__row--total">
                <span><?= e(t('cart.subtotal')) ?></span>
                <span data-cart-subtotal></span>
            </div>
            <p class="summary__note"><?= e(t('cart.shipping_calc')) ?></p>
            <a class="btn btn--block" href="<?= e(lang_url('checkout')) ?>"><?= e(t('cart.checkout')) ?></a>
            <a class="btn btn--ghost btn--block" style="margin-top:.8rem;"
               href="<?= e(lang_url('cart')) ?>"><?= e(t('cart.title')) ?></a>
        </div>
    </div>
</div>
