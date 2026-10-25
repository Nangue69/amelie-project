<?php
/** @var array $page */
/** @var array $faqs */
?>
<div class="page-head">
    <div class="wrap">
        <h1><?= e((string) $page['title']) ?></h1>
        <?php if (!empty($page['intro'])): ?>
            <p class="lede"><?= e((string) $page['intro']) ?></p>
        <?php endif; ?>
    </div>
</div>

<div class="wrap section">
    <div class="faq-list accordion">
        <?php foreach ($faqs as $index => $faq): ?>
            <div class="accordion__item">
                <h2 style="margin:0;">
                    <button type="button" class="accordion__trigger" aria-expanded="false"
                            aria-controls="faq-<?= (int) $faq['id'] ?>">
                        <?= e((string) $faq['question']) ?>
                    </button>
                </h2>
                <div class="accordion__panel prose" id="faq-<?= (int) $faq['id'] ?>" hidden>
                    <p><?= e((string) $faq['answer']) ?></p>
                </div>
            </div>
        <?php endforeach; ?>
    </div>

    <?php if (!empty($page['body'])): ?>
        <div class="prose wrap--text" style="margin-top:4.8rem;"><?= safe_html((string) $page['body']) ?></div>
    <?php endif; ?>

    <p class="text-center" style="margin-top:4.8rem;">
        <a class="link-underline" href="<?= e(lang_url('contact')) ?>"><?= e(t('nav.contact')) ?></a>
    </p>
</div>
