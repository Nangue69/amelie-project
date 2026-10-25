<?php

use App\Core\Settings;
use App\Core\View;

$social = array_filter([
    'Instagram' => Settings::get('instagram_url'),
    'Facebook'  => Settings::get('facebook_url'),
    'TikTok'    => Settings::get('tiktok_url'),
    'Pinterest' => Settings::get('pinterest_url'),
]);
?>
<footer class="footer">
    <div class="wrap">
        <div class="footer__grid">
            <div class="footer__brand">
                <img src="<?= e(asset('img/brand/Wordmark_Oro_Trasparente.svg')) ?>"
                     alt="<?= e($siteName) ?>" width="220" height="36" loading="lazy">
                <p><?= e(t('home.hero.line1')) ?> <?= e(t('home.hero.line2')) ?></p>

                <?php if ($social !== []): ?>
                    <div class="footer__social">
                        <?php foreach ($social as $name => $href): ?>
                            <a href="<?= e($href) ?>" rel="noopener noreferrer" target="_blank"
                               aria-label="<?= e($name) ?>"><?= View::partial('icon-social', ['name' => $name]) ?></a>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>
            </div>

            <div>
                <h2 class="footer__title"><?= e(t('footer.shop')) ?></h2>
                <ul class="footer__list">
                    <li><a href="<?= e(lang_url('shop')) ?>"><?= e(t('shop.all')) ?></a></li>
                    <?php foreach ($categories as $category): ?>
                        <li><a href="<?= e(lang_url('shop/' . $category['slug'])) ?>"><?= e($category['name']) ?></a></li>
                    <?php endforeach; ?>
                    <li><a href="<?= e(lang_url('journal')) ?>"><?= e(t('nav.journal')) ?></a></li>
                </ul>
            </div>

            <div>
                <h2 class="footer__title"><?= e(t('footer.help')) ?></h2>
                <ul class="footer__list">
                    <?php foreach ($footerLinks['help'] as $link): ?>
                        <li><a href="<?= e(lang_url($link['slug'])) ?>"><?= e($link['title']) ?></a></li>
                    <?php endforeach; ?>
                </ul>
            </div>

            <div>
                <h2 class="footer__title"><?= e(t('footer.legal')) ?></h2>
                <ul class="footer__list">
                    <li><a href="<?= e(lang_url('our-story')) ?>"><?= e(t('nav.story')) ?></a></li>
                    <?php foreach ($footerLinks['legal'] as $link): ?>
                        <li><a href="<?= e(lang_url($link['slug'])) ?>"><?= e($link['title']) ?></a></li>
                    <?php endforeach; ?>
                </ul>
            </div>
        </div>

        <div class="footer__bottom">
            <p style="margin:0;">&copy; <?= date('Y') ?> <?= e($siteName) ?>. <?= e(t('footer.rights')) ?></p>

            <div class="payment-marks" aria-label="<?= e(t('checkout.payment')) ?>">
                <span>Visa</span><span>Mastercard</span><span>Amex</span><span>iDEAL</span><span>Bancontact</span>
            </div>

            <button type="button" data-cookie-settings><?= e(t('cookie.configure')) ?></button>
        </div>
    </div>
</footer>
