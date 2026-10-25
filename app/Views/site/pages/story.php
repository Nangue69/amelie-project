<?php
use App\Core\Settings;
use App\Core\View;
/** @var array $page */
/** @var array $products */
?>
<section class="story">
    <div class="wrap">
        <img class="story__ornament" src="<?= e(asset('img/brand/Monogramma_AM_Oro_Trasparente.svg')) ?>"
             alt="" width="60" height="60" aria-hidden="true">

        <p class="eyebrow"><?= e((string) $page['title']) ?></p>
        <h1 style="font-size:clamp(3.2rem,6vw,5.6rem);"><?= e((string) $page['intro']) ?></h1>
        <hr class="rule rule--center">

        <div class="story__body prose"><?= safe_html((string) $page['body']) ?></div>
    </div>
</section>

<section class="section">
    <div class="wrap split">
        <div class="split__media">
            <img src="<?= e(img(Settings::get('home_story_image'))) ?>"
                 alt="<?= e((string) $page['title']) ?>" loading="lazy" width="800" height="1000">
        </div>
        <div class="split__body">
            <p class="eyebrow"><?= e(t('home.craft.eyebrow')) ?></p>
            <h2><?= e(t('home.craft.title')) ?></h2>
            <hr class="rule">
            <p><?= e(t('home.craft.text')) ?></p>
            <a class="link-underline" href="<?= e(lang_url('shop')) ?>"><?= e(t('home.hero.cta')) ?></a>
        </div>
    </div>
</section>

<?php if ($products !== []): ?>
<section class="section section--alt">
    <div class="wrap">
        <div class="section-head section-head--center"><h2><?= e(t('home.collection.title')) ?></h2></div>
        <div class="product-grid product-grid--pair">
            <?php foreach ($products as $product): ?>
                <?= View::partial('product-card', ['product' => $product]) ?>
            <?php endforeach; ?>
        </div>
    </div>
</section>
<?php endif; ?>
