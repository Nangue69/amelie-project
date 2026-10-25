<?php

use App\Core\Flash;
use App\Core\Settings;

/** @var array $page */

$errors  = Flash::errors();
$invalid = static fn (string $field): string => isset($errors[$field]) ? ' aria-invalid="true"' : '';

$social = array_filter([
    'Instagram' => Settings::get('instagram_url'),
    'Facebook'  => Settings::get('facebook_url'),
    'TikTok'    => Settings::get('tiktok_url'),
    'Pinterest' => Settings::get('pinterest_url'),
]);
?>
<div class="page-head">
    <div class="wrap">
        <h1><?= e((string) $page['title']) ?></h1>
        <?php if (!empty($page['intro'])): ?>
            <p class="lede"><?= e((string) $page['intro']) ?></p>
        <?php endif; ?>
    </div>
</div>

<div class="wrap">
    <div class="contact-layout">
        <form action="<?= e(lang_url('contact')) ?>" method="post" novalidate>
            <?= csrf_field() ?>

            <div class="form-grid">
                <div class="field">
                    <label class="field__label" for="name"><?= e(t('form.name')) ?></label>
                    <input class="input" type="text" id="name" name="name" required autocomplete="name"
                           value="<?= e(Flash::old('name')) ?>"<?= $invalid('name') ?>>
                </div>

                <div class="field">
                    <label class="field__label" for="email"><?= e(t('form.email')) ?></label>
                    <input class="input" type="email" id="email" name="email" required autocomplete="email"
                           value="<?= e(Flash::old('email')) ?>"<?= $invalid('email') ?>>
                </div>

                <div class="field field--full">
                    <label class="field__label" for="subject"><?= e(t('form.subject')) ?></label>
                    <input class="input" type="text" id="subject" name="subject"
                           value="<?= e(Flash::old('subject')) ?>">
                </div>

                <div class="field field--full">
                    <label class="field__label" for="message"><?= e(t('form.message')) ?></label>
                    <textarea class="textarea" id="message" name="message" required<?= $invalid('message') ?>><?= e(Flash::old('message')) ?></textarea>
                </div>
            </div>

            <div class="visually-hidden" aria-hidden="true">
                <label for="website">No rellenar</label>
                <input type="text" id="website" name="website" tabindex="-1" autocomplete="off">
            </div>

            <button class="btn" type="submit"><?= e(t('form.send')) ?></button>
        </form>

        <aside class="contact-card">
            <h2 style="font-size:2rem;"><?= e(t('nav.contact')) ?></h2>
            <dl>
                <dt><?= e(t('form.email')) ?></dt>
                <dd><a href="mailto:<?= e(Settings::get('contact_email')) ?>" style="border-bottom:1px solid var(--accent);">
                    <?= e(Settings::get('contact_email')) ?></a></dd>

                <?php if (Settings::get('contact_phone') !== ''): ?>
                    <dt><?= e(t('form.phone')) ?></dt>
                    <dd><?= e(Settings::get('contact_phone')) ?></dd>
                <?php endif; ?>

                <?php if (Settings::get('contact_address') !== ''): ?>
                    <dt><?= e(t('form.address')) ?></dt>
                    <dd><?= e(Settings::get('contact_address')) ?></dd>
                <?php endif; ?>
            </dl>

            <?php if ($social !== []): ?>
                <dl>
                    <dt><?= e(t('footer.follow')) ?></dt>
                    <dd>
                        <?php foreach ($social as $name => $href): ?>
                            <a href="<?= e($href) ?>" target="_blank" rel="noopener noreferrer"
                               style="margin-right:1.2rem;border-bottom:1px solid var(--accent);"><?= e($name) ?></a>
                        <?php endforeach; ?>
                    </dd>
                </dl>
            <?php endif; ?>

            <hr class="rule">

            <p style="font-size:1.4rem;color:var(--ink-soft);">
                <?= e(t('product.sizeguide')) ?>:
                <a href="<?= e(lang_url('size-guide')) ?>" style="border-bottom:1px solid var(--accent);">
                    <?= e(t('common.view')) ?></a>
            </p>
        </aside>
    </div>

    <?php if (!empty($page['body'])): ?>
        <div class="prose wrap--text" style="margin-bottom:7.2rem;"><?= safe_html((string) $page['body']) ?></div>
    <?php endif; ?>
</div>
