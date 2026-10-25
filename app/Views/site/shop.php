<?php

use App\Core\View;

/** @var array $products */
/** @var array|null $category */
/** @var string $sort */
/** @var array $sorts */

$basePath = $category === null ? 'shop' : 'shop/' . $category['slug'];
?>
<div class="page-head">
    <div class="wrap">
        <nav class="breadcrumb" aria-label="breadcrumb">
            <ol>
                <li><a href="<?= e(lang_url('')) ?>"><?= e(t('common.home')) ?></a></li>
                <li aria-hidden="true">·</li>
                <li><a href="<?= e(lang_url('shop')) ?>"><?= e(t('shop.title')) ?></a></li>
                <?php if ($category !== null): ?>
                    <li aria-hidden="true">·</li>
                    <li><?= e((string) $category['name']) ?></li>
                <?php endif; ?>
            </ol>
        </nav>

        <h1><?= e($category['name'] ?? t('shop.title')) ?></h1>
        <p><?= e($category['description'] ?? t('shop.subtitle')) ?></p>
    </div>
</div>

<div class="wrap">
    <div class="shop-bar">
        <ul class="shop-filters">
            <li>
                <a href="<?= e(lang_url('shop')) ?>" <?= $category === null ? 'aria-current="page"' : '' ?>>
                    <?= e(t('shop.all')) ?>
                </a>
            </li>
            <?php foreach ($categories as $item): ?>
                <li>
                    <a href="<?= e(lang_url('shop/' . $item['slug'])) ?>"
                       <?= ($category['slug'] ?? '') === $item['slug'] ? 'aria-current="page"' : '' ?>>
                        <?= e((string) $item['name']) ?>
                    </a>
                </li>
            <?php endforeach; ?>
        </ul>

        <form class="shop-sort" method="get" action="<?= e(lang_url($basePath)) ?>">
            <label for="sort"><?= e(t('shop.sort')) ?></label>
            <select class="select" name="sort" id="sort" onchange="this.form.submit()">
                <?php foreach ($sorts as $option): ?>
                    <option value="<?= e($option) ?>" <?= $sort === $option ? 'selected' : '' ?>>
                        <?= e(t('shop.sort.' . $option)) ?>
                    </option>
                <?php endforeach; ?>
            </select>
            <noscript><button class="btn btn--sm" type="submit"><?= e(t('shop.sort')) ?></button></noscript>
        </form>
    </div>

    <?php if ($products === []): ?>
        <p class="empty-state"><?= e(t('shop.empty')) ?></p>
    <?php else: ?>
        <p class="visually-hidden"><?= count($products) ?> <?= e(t('shop.results')) ?></p>
        <div class="product-grid" style="padding-bottom:7.2rem;">
            <?php foreach ($products as $product): ?>
                <?= View::partial('product-card', ['product' => $product]) ?>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>
</div>
