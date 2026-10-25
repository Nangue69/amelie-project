<?php
/** @var array $post */
/** @var array $others */
?>
<article>
    <div class="page-head">
        <div class="wrap wrap--text">
            <time class="post-card__date" datetime="<?= e(format_date($post['published_at'], 'Y-m-d')) ?>">
                <?= e(format_date($post['published_at'])) ?>
            </time>
            <h1><?= e((string) $post['title']) ?></h1>
            <?php if (!empty($post['excerpt'])): ?>
                <p class="lede"><?= e((string) $post['excerpt']) ?></p>
            <?php endif; ?>
        </div>
    </div>

    <?php if (!empty($post['cover_image'])): ?>
        <div class="wrap" style="padding-top:4.8rem;">
            <img src="<?= e(img((string) $post['cover_image'])) ?>" alt="<?= e((string) $post['title']) ?>"
                 width="1200" height="800" style="width:100%;aspect-ratio:3/2;object-fit:cover;">
        </div>
    <?php endif; ?>

    <div class="wrap wrap--text section">
        <div class="prose"><?= safe_html((string) $post['body']) ?></div>

        <p style="margin-top:4.8rem;">
            <a class="link-underline" href="<?= e(lang_url('journal')) ?>"><?= e(t('journal.back')) ?></a>
        </p>
    </div>
</article>

<?php if ($others !== []): ?>
<section class="section section--alt">
    <div class="wrap">
        <div class="section-head section-head--center"><h2><?= e(t('home.journal.title')) ?></h2></div>
        <div class="post-grid">
            <?php foreach ($others as $other): ?>
                <article class="post-card">
                    <a class="post-card__media" href="<?= e(lang_url('journal/' . $other['slug'])) ?>" tabindex="-1" aria-hidden="true">
                        <img src="<?= e(img((string) $other['cover_image'])) ?>" alt="" loading="lazy" width="600" height="450">
                    </a>
                    <h3 style="margin-top:1.6rem;"><a href="<?= e(lang_url('journal/' . $other['slug'])) ?>"><?= e((string) $other['title']) ?></a></h3>
                    <p><?= e(excerpt((string) $other['excerpt'], 120)) ?></p>
                </article>
            <?php endforeach; ?>
        </div>
    </div>
</section>
<?php endif; ?>
