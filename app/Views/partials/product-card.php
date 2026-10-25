<?php
/** @var array $product */
$stock   = (int) ($product['stock'] ?? 0);
$href    = lang_url('product/' . $product['slug']);
$hover   = (string) ($product['image_hover'] ?? '');
$compare = $product['compare_price'] ?? null;
?>
<article class="product-card">
    <?php if ($stock < 1): ?>
        <span class="product-card__flag product-card__flag--out"><?= e(t('product.sold_out')) ?></span>
    <?php elseif ((int) ($product['is_featured'] ?? 0) === 1): ?>
        <span class="product-card__flag"><?= e(t('shop.sort.featured')) ?></span>
    <?php endif; ?>

    <a class="product-card__media" href="<?= e($href) ?>" tabindex="-1" aria-hidden="true">
        <img src="<?= e(img((string) $product['image'])) ?>" alt="" loading="lazy" width="600" height="800">
        <?php if ($hover !== ''): ?>
            <img src="<?= e(img($hover)) ?>" alt="" loading="lazy" width="600" height="800">
        <?php endif; ?>
    </a>

    <div class="product-card__body">
        <h3 class="product-card__name"><a href="<?= e($href) ?>"><?= e((string) $product['name']) ?></a></h3>

        <?php if (!empty($product['tagline'])): ?>
            <p class="product-card__tagline"><?= e((string) $product['tagline']) ?></p>
        <?php endif; ?>

        <p class="product-card__price">
            <?php if ($compare !== null && (float) $compare > (float) $product['price']): ?>
                <s><?= money($compare) ?></s>
            <?php endif; ?>
            <?= money($product['price']) ?>
        </p>
    </div>
</article>
