<?php
/** @var array $posts */
?>
<div class="page-head">
    <div class="wrap">
        <p class="eyebrow"><?= e(t('nav.journal')) ?></p>
        <h1><?= e(t('journal.title')) ?></h1>
        <p><?= e(t('journal.subtitle')) ?></p>
    </div>
</div>

<div class="wrap section">
    <?php if ($posts === []): ?>
        <p class="empty-state"><?= e(t('journal.empty')) ?></p>
    <?php else: ?>
        <div class="post-grid">
            <?php foreach ($posts as $post): ?>
                <article class="post-card">
                    <a class="post-card__media" href="<?= e(lang_url('journal/' . $post['slug'])) ?>" tabindex="-1" aria-hidden="true">
                        <img src="<?= e(img((string) $post['cover_image'])) ?>" alt="" loading="lazy" width="600" height="450">
                    </a>
                    <time class="post-card__date" datetime="<?= e(format_date($post['published_at'], 'Y-m-d')) ?>">
                        <?= e(format_date($post['published_at'])) ?>
                    </time>
                    <h2><a href="<?= e(lang_url('journal/' . $post['slug'])) ?>"><?= e((string) $post['title']) ?></a></h2>
                    <p><?= e(excerpt((string) $post['excerpt'], 150)) ?></p>
                </article>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>
</div>
