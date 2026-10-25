<?php

use App\Core\Settings;

if (!Settings::bool('cookie_banner', true)) {
    return;
}
?>
<div class="cookie-bar" id="cookie-bar" role="region" aria-label="<?= e(t('cookie.title')) ?>" hidden>
    <div class="cookie-bar__text">
        <strong><?= e(t('cookie.title')) ?></strong>
        <?= e(t('cookie.text')) ?>
        <a href="<?= e(lang_url('cookies')) ?>" style="text-decoration:underline;"><?= e(t('footer.legal')) ?></a>
    </div>
    <div class="cookie-bar__actions">
        <button type="button" class="btn btn--sm btn--ghost" data-cookie-settings><?= e(t('cookie.configure')) ?></button>
        <button type="button" class="btn btn--sm btn--ghost" data-cookie-reject><?= e(t('cookie.reject')) ?></button>
        <button type="button" class="btn btn--sm" data-cookie-accept><?= e(t('cookie.accept')) ?></button>
    </div>
</div>

<div class="modal" id="cookie-modal" role="dialog" aria-modal="true" aria-labelledby="cookie-modal-title" hidden>
    <div class="modal__panel">
        <button type="button" class="modal__close" data-close aria-label="<?= e(t('common.close')) ?>">&times;</button>
        <h2 id="cookie-modal-title"><?= e(t('cookie.title')) ?></h2>
        <p class="lede"><?= e(t('cookie.text')) ?></p>

        <form data-cookie-form>
            <div class="cookie-pref">
                <input type="checkbox" checked disabled aria-label="<?= e(t('cookie.necessary')) ?>">
                <div>
                    <strong><?= e(t('cookie.necessary')) ?></strong>
                    <p><?= e(t('cookie.necessary_desc')) ?></p>
                </div>
            </div>

            <label class="cookie-pref">
                <input type="checkbox" name="analytics">
                <div>
                    <strong><?= e(t('cookie.analytics')) ?></strong>
                    <p><?= e(t('cookie.analytics_desc')) ?></p>
                </div>
            </label>

            <label class="cookie-pref">
                <input type="checkbox" name="marketing">
                <div>
                    <strong><?= e(t('cookie.marketing')) ?></strong>
                    <p><?= e(t('cookie.marketing_desc')) ?></p>
                </div>
            </label>

            <div style="display:flex;gap:.8rem;flex-wrap:wrap;margin-top:2.4rem;">
                <button type="submit" class="btn"><?= e(t('cookie.save')) ?></button>
                <button type="button" class="btn btn--ghost" data-cookie-accept><?= e(t('cookie.accept')) ?></button>
            </div>
        </form>
    </div>
</div>
