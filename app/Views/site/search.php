<?php
use App\Core\View;
/** @var array $products */
/** @var string $term */
?>
<div class="page-head">
    <div class="wrap">
        <h1><?= e(t('search.title')) ?></h1>
        <form action="<?= e(lang_url('search')) ?>" method="get" role="search"
              style="display:flex;gap:.8rem;max-width:52rem;margin:2.4rem auto 0;">
            <label class="visually-hidden" for="q"><?= e(t('search.title')) ?></label>
            <input class="input" type="search" id="q" name="q" value="<?= e($term) ?>"
                   placeholder="<?= e(t('search.placeholder')) ?>">
            <button class="btn" type="submit"><?= e(t('nav.search')) ?></button>
        </form>
    </div>
</div>

<div class="wrap section">
    <?php if ($term !== '' && $products === []): ?>
        <p class="empty-state"><?= e(t('search.empty')) ?></p>
    <?php elseif ($products !== []): ?>
        <p style="margin-bottom:2.4rem;color:var(--ink-soft);"><?= count($products) ?> <?= e(t('shop.results')) ?></p>
        <div class="product-grid">
            <?php foreach ($products as $product): ?>
                <?= View::partial('product-card', ['product' => $product]) ?>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>
</div>
