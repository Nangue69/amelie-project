<?php
/** @var bool $done */
?>
<div class="wrap error-page">
    <div>
        <h1><?= e($done ? t('footer.newsletter_title') : t('error.404.title')) ?></h1>
        <p class="lede"><?= e($done ? t('msg.updated') : t('msg.error')) ?></p>
        <a class="btn" href="<?= e(lang_url('')) ?>"><?= e(t('common.home')) ?></a>
    </div>
</div>
