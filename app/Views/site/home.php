<?php

use App\Core\Settings;
use App\Core\View;

/** @var array $products */
/** @var array $posts */
?>
<section class="hero">
    <div class="hero__media">
        <img src="<?= e(img(Settings::get('home_hero_image'))) ?>"
             alt="<?= e(t('home.hero.title')) ?>" width="1200" height="1600" fetchpriority="high">
    </div>

    <div class="wrap hero__inner">
        <div class="hero__content">
            <p class="hero__eyebrow"><?= e(t('home.hero.eyebrow')) ?></p>
            <h1 class="hero__title"><?= e(t('home.hero.title')) ?></h1>

            <div class="hero__lines">
                <p><?= e(t('home.hero.line1')) ?></p>
                <p><?= e(t('home.hero.line2')) ?></p>
                <p><?= e(t('home.hero.line3')) ?></p>
            </div>

            <a class="btn btn--light" href="<?= e(lang_url('shop')) ?>"><?= e(t('home.hero.cta')) ?></a>
        </div>
    </div>
</section>

<section class="section">
    <div class="wrap">
        <div class="section-head section-head--center">
            <p class="eyebrow"><?= e(t('home.collection.eyebrow')) ?></p>
            <h2><?= e(t('home.collection.title')) ?></h2>
            <p><?= e(t('home.collection.text')) ?></p>
        </div>

        <div class="product-grid product-grid--pair">
            <?php foreach ($products as $product): ?>
                <?= View::partial('product-card', ['product' => $product]) ?>
            <?php endforeach; ?>
        </div>

        <p class="text-center" style="margin-top:4.8rem;">
            <a class="link-underline" href="<?= e(lang_url('shop')) ?>"><?= e(t('home.hero.cta')) ?></a>
        </p>
    </div>
</section>

<section class="section section--alt">
    <div class="wrap split">
        <div class="split__media">
            <img src="<?= e(img(Settings::get('home_story_image'))) ?>"
                 alt="<?= e(t('home.philosophy.title')) ?>" loading="lazy" width="800" height="1000">
        </div>

        <div class="split__body">
            <p class="eyebrow"><?= e(t('home.philosophy.eyebrow')) ?></p>
            <h2><?= e(t('home.philosophy.title')) ?></h2>
            <hr class="rule">
            <p class="lede"><?= e(t('home.philosophy.text')) ?></p>
            <a class="link-underline" href="<?= e(lang_url('our-story')) ?>"><?= e(t('home.philosophy.cta')) ?></a>
        </div>
    </div>
</section>

<section class="section">
    <div class="wrap split split--reverse">
        <div class="split__media">
            <img src="<?= e(img(Settings::get('home_craft_image'))) ?>"
                 alt="<?= e(t('home.craft.title')) ?>" loading="lazy" width="800" height="1000">
        </div>

        <div class="split__body">
            <p class="eyebrow"><?= e(t('home.craft.eyebrow')) ?></p>
            <h2><?= e(t('home.craft.title')) ?></h2>
            <hr class="rule">
            <p><?= e(t('home.craft.text')) ?></p>
        </div>
    </div>

    <div class="wrap">
        <div class="craft-points">
            <?php for ($i = 1; $i <= 3; $i++): ?>
                <div class="craft-point">
                    <h3><?= e(t("home.craft.p{$i}.title")) ?></h3>
                    <p><?= e(t("home.craft.p{$i}.text")) ?></p>
                </div>
            <?php endfor; ?>
        </div>
    </div>
</section>

<section class="manifesto">
    <div class="manifesto__media">
        <img src="<?= e(img(Settings::get('home_manifesto_image'))) ?>" alt="" loading="lazy" aria-hidden="true">
    </div>

    <div class="manifesto__body">
        <h2><?= e(t('home.manifesto.title')) ?></h2>
        <p><?= e(t('home.manifesto.text')) ?></p>
        <a class="btn btn--light" style="margin-top:2.4rem;"
           href="<?= e(lang_url('our-story')) ?>"><?= e(t('home.philosophy.cta')) ?></a>
    </div>
</section>

<?php if ($posts !== []): ?>
<section class="section">
    <div class="wrap">
        <div class="section-head section-head--center">
            <p class="eyebrow"><?= e(t('nav.journal')) ?></p>
            <h2><?= e(t('home.journal.title')) ?></h2>
        </div>

        <div class="post-grid">
            <?php foreach ($posts as $post): ?>
                <article class="post-card">
                    <a class="post-card__media" href="<?= e(lang_url('journal/' . $post['slug'])) ?>" tabindex="-1" aria-hidden="true">
                        <img src="<?= e(img((string) $post['cover_image'])) ?>" alt="" loading="lazy" width="600" height="450">
                    </a>
                    <time class="post-card__date" datetime="<?= e(format_date($post['published_at'], 'Y-m-d')) ?>">
                        <?= e(format_date($post['published_at'])) ?>
                    </time>
                    <h3><a href="<?= e(lang_url('journal/' . $post['slug'])) ?>"><?= e((string) $post['title']) ?></a></h3>
                    <p><?= e(excerpt((string) $post['excerpt'], 130)) ?></p>
                </article>
            <?php endforeach; ?>
        </div>

        <p class="text-center" style="margin-top:4.8rem;">
            <a class="link-underline" href="<?= e(lang_url('journal')) ?>"><?= e(t('home.journal.cta')) ?></a>
        </p>
    </div>
</section>
<?php endif; ?>

<section class="newsletter">
    <div class="wrap">
        <p class="eyebrow"><?= e(t('footer.newsletter_title')) ?></p>
        <h2><?= e(t('footer.newsletter_text')) ?></h2>

        <form class="newsletter__form" action="<?= e(lang_url('newsletter')) ?>" method="post" data-newsletter="home">
            <?= csrf_field() ?>
            <label class="visually-hidden" for="newsletter-home"><?= e(t('form.email')) ?></label>
            <input class="input" type="email" id="newsletter-home" name="email" required
                   placeholder="<?= e(t('footer.newsletter_placeholder')) ?>" autocomplete="email">
            <button class="btn" type="submit"><?= e(t('footer.newsletter_cta')) ?></button>
        </form>

        <p class="newsletter__legal"><?= e(t('footer.newsletter_legal')) ?></p>
    </div>
</section>
