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
<section class="coming">
    <div class="coming__media">
        <img src="<?= e(img(Settings::get('home_hero_image'))) ?>" alt="" aria-hidden="true" fetchpriority="high">
    </div>

    <div class="coming__body">
        <img class="coming__logo" src="<?= e(asset('img/brand/Logo_Avorio_Trasparente.svg')) ?>"
             alt="<?= e($siteName . ' — ' . $siteTagline) ?>" width="280" height="121">

        <p class="coming__badge"><?= e(t('coming.badge')) ?></p>
        <h1><?= e(t('coming.title')) ?></h1>

        <div class="coming__lines">
            <p><?= e(t('home.hero.line1')) ?></p>
            <p><?= e(t('home.hero.line2')) ?></p>
        </div>

        <p style="color:rgba(246,240,230,.8);"><?= e(t('coming.text')) ?></p>

        <form class="coming__form" action="<?= e(lang_url('newsletter')) ?>" method="post" data-newsletter="coming-soon">
            <?= csrf_field() ?>
            <label class="visually-hidden" for="coming-email"><?= e(t('form.email')) ?></label>
            <input class="input" type="email" id="coming-email" name="email" required
                   placeholder="<?= e(t('coming.placeholder')) ?>" autocomplete="email">
            <button class="btn btn--light" type="submit"><?= e(t('coming.cta')) ?></button>
        </form>

        <?php if ($social !== []): ?>
            <div class="coming__social">
                <?php foreach ($social as $name => $href): ?>
                    <a href="<?= e($href) ?>" target="_blank" rel="noopener noreferrer"
                       aria-label="<?= e($name) ?>"><?= View::partial('icon-social', ['name' => $name]) ?></a>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>

        <div class="drawer__langs" style="justify-content:center;border-color:rgba(246,240,230,.2);">
            <?php foreach ($languages as $code => $language): ?>
                <a href="<?= e(lang_url('', $code)) ?>" lang="<?= e($code) ?>"
                   style="color:rgba(246,240,230,.7);"
                   <?= $code === $lang ? 'aria-current="true"' : '' ?>><?= e($language['native_name']) ?></a>
            <?php endforeach; ?>
        </div>
    </div>
</section>
