<?php

use App\Core\View;
use App\Models\SizeGuide;

/** @var array $product */
/** @var array $images */
/** @var array $variants */
/** @var array $braSizes */
/** @var array $briefSizes */
/** @var array $sizes */
/** @var array $guides */
/** @var array $related */

$isPair  = $product['size_mode'] === 'bra_brief';
$stock   = (int) $product['stock'];
$primary = $images[0] ?? ['path' => '', 'alt' => $product['name']];

$variantData = array_map(static fn (array $v): array => [
    'id'         => (int) $v['id'],
    'size'       => $v['size'],
    'size_bra'   => $v['size_bra'],
    'size_brief' => $v['size_brief'],
    'stock'      => (int) $v['stock'],
], $variants);

$guideKeys = $isPair ? ['bra', 'brief'] : ['corset'];

$panels = array_filter([
    'description'      => $product['description'],
    'composition'      => $product['composition'],
    'care'             => $product['care'],
    'shipping_returns' => $product['shipping_returns'],
]);

$panelLabels = [
    'description'      => t('product.description'),
    'composition'      => t('product.composition'),
    'care'             => t('product.care'),
    'shipping_returns' => t('product.shipping'),
];
?>
<div class="wrap">
    <nav class="breadcrumb" style="justify-content:flex-start;padding-top:2.4rem;" aria-label="breadcrumb">
        <ol>
            <li><a href="<?= e(lang_url('')) ?>"><?= e(t('common.home')) ?></a></li>
            <li aria-hidden="true">·</li>
            <li><a href="<?= e(lang_url('shop')) ?>"><?= e(t('shop.title')) ?></a></li>
            <?php if (!empty($product['category_slug'])): ?>
                <li aria-hidden="true">·</li>
                <li><a href="<?= e(lang_url('shop/' . $product['category_slug'])) ?>"><?= e((string) $product['category_name']) ?></a></li>
            <?php endif; ?>
        </ol>
    </nav>

    <div class="product">
        <div class="gallery">
            <div class="gallery__main">
                <img data-gallery-main src="<?= e(img((string) $primary['path'])) ?>"
                     alt="<?= e((string) ($primary['alt'] ?: $product['name'])) ?>"
                     width="900" height="1200" fetchpriority="high">
            </div>

            <?php if (count($images) > 1): ?>
                <div class="gallery__thumbs">
                    <?php foreach ($images as $index => $image): ?>
                        <button type="button" class="gallery__thumb" data-gallery-thumb
                                data-src="<?= e(img((string) $image['path'])) ?>"
                                data-alt="<?= e((string) $image['alt']) ?>"
                                aria-current="<?= $index === 0 ? 'true' : 'false' ?>"
                                aria-label="<?= e((string) ($image['alt'] ?: $product['name'])) ?>">
                            <img src="<?= e(img((string) $image['path'])) ?>" alt="" loading="lazy" width="200" height="200">
                        </button>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>
        </div>

        <div class="product__info">
            <?php if (!empty($product['category_name'])): ?>
                <p class="product__category"><?= e((string) $product['category_name']) ?></p>
            <?php endif; ?>

            <h1 class="product__title"><?= e((string) $product['name']) ?></h1>

            <?php if (!empty($product['tagline'])): ?>
                <p class="product__tagline"><?= e((string) $product['tagline']) ?></p>
            <?php endif; ?>

            <p class="product__price">
                <?php if ($product['compare_price'] !== null && (float) $product['compare_price'] > (float) $product['price']): ?>
                    <s><?= money($product['compare_price']) ?></s>
                <?php endif; ?>
                <?= money($product['price']) ?>
            </p>

            <?php if (!empty($product['short_description'])): ?>
                <p class="product__short"><?= e((string) $product['short_description']) ?></p>
            <?php endif; ?>

            <script type="application/json" id="variant-data"><?= json_encode($variantData, JSON_UNESCAPED_UNICODE) ?></script>

            <form data-buy-form data-size-mode="<?= e((string) $product['size_mode']) ?>"
                  action="<?= e(lang_url('cart/add')) ?>" method="post">
                <?= csrf_field() ?>
                <input type="hidden" name="variant_id" value="" data-variant-id>

                <?php if ($isPair): ?>
                    <fieldset>
                        <div class="size-row">
                            <legend class="size-row__label"><?= e(t('product.size_bra')) ?></legend>
                            <button type="button" class="size-guide-btn" data-open="size-modal"><?= e(t('product.sizeguide')) ?></button>
                        </div>
                        <div class="size-options">
                            <?php foreach ($braSizes as $size): ?>
                                <label class="size-option">
                                    <input type="radio" name="size_bra" value="<?= e($size) ?>">
                                    <span><?= e($size) ?></span>
                                </label>
                            <?php endforeach; ?>
                        </div>
                    </fieldset>

                    <fieldset>
                        <div class="size-row">
                            <legend class="size-row__label"><?= e(t('product.size_brief')) ?></legend>
                        </div>
                        <div class="size-options">
                            <?php foreach ($briefSizes as $size): ?>
                                <label class="size-option">
                                    <input type="radio" name="size_brief" value="<?= e($size) ?>">
                                    <span><?= e($size) ?></span>
                                </label>
                            <?php endforeach; ?>
                        </div>
                    </fieldset>
                <?php else: ?>
                    <fieldset>
                        <div class="size-row">
                            <legend class="size-row__label"><?= e(t('product.size')) ?></legend>
                            <button type="button" class="size-guide-btn" data-open="size-modal"><?= e(t('product.sizeguide')) ?></button>
                        </div>
                        <div class="size-options">
                            <?php foreach ($sizes as $size): ?>
                                <?php
                                $variantForSize = null;
                                foreach ($variants as $variant) {
                                    if ($variant['size'] === $size) {
                                        $variantForSize = $variant;
                                        break;
                                    }
                                }
                                $soldOut = $variantForSize === null || (int) $variantForSize['stock'] < 1;
                                ?>
                                <label class="size-option">
                                    <input type="radio" name="size" value="<?= e($size) ?>" <?= $soldOut ? 'disabled' : '' ?>>
                                    <span><?= e($size) ?></span>
                                </label>
                            <?php endforeach; ?>
                        </div>
                    </fieldset>
                <?php endif; ?>

                <p class="stock-line" data-stock-line>
                    <?= e($isPair ? t('product.choose_both') : t('product.select_size')) ?>
                </p>

                <div class="buy-row">
                    <div class="qty">
                        <button type="button" data-qty-step="-1" aria-label="-">&minus;</button>
                        <label class="visually-hidden" for="qty"><?= e(t('product.quantity')) ?></label>
                        <input type="number" id="qty" name="quantity" value="1" min="1" max="10" data-qty>
                        <button type="button" data-qty-step="1" aria-label="+">+</button>
                    </div>

                    <button class="btn btn--block" type="submit" data-add-button <?= $stock < 1 ? 'disabled' : '' ?>>
                        <?= e($stock < 1 ? t('product.sold_out') : t('product.add')) ?>
                    </button>
                </div>
            </form>

            <?php if (!empty($product['sku'])): ?>
                <p class="product__meta"><?= e(t('product.sku')) ?>: <?= e((string) $product['sku']) ?></p>
            <?php endif; ?>

            <p class="product__notice"><?= e(t('product.notice')) ?></p>

            <div class="accordion">
                <?php $index = 0; foreach ($panels as $key => $content): $index++; ?>
                    <div class="accordion__item">
                        <h2 style="margin:0;">
                            <button type="button" class="accordion__trigger"
                                    aria-expanded="<?= $index === 1 ? 'true' : 'false' ?>"
                                    aria-controls="panel-<?= e($key) ?>">
                                <?= e($panelLabels[$key]) ?>
                            </button>
                        </h2>
                        <div class="accordion__panel prose" id="panel-<?= e($key) ?>" <?= $index === 1 ? '' : 'hidden' ?>>
                            <?= safe_html((string) $content) ?>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
    </div>
</div>

<div class="modal" id="size-modal" role="dialog" aria-modal="true" aria-labelledby="size-modal-title" hidden>
    <div class="modal__panel">
        <button type="button" class="modal__close" data-close aria-label="<?= e(t('common.close')) ?>">&times;</button>
        <h2 id="size-modal-title"><?= e(t('product.sizeguide')) ?></h2>

        <?php foreach ($guides as $guide): ?>
            <?php if (!in_array($guide['gkey'], $guideKeys, true) || $guide['rows'] === []) { continue; } ?>
            <?php $columns = SizeGuide::usedColumns($guide['rows']); ?>

            <div class="guide-block">
                <h3><?= e(SizeGuide::title((string) $guide['gkey'], $lang)) ?></h3>
                <div class="table-scroll">
                    <table class="data-table">
                        <thead>
                            <tr>
                                <th><?= e(t('product.size')) ?></th>
                                <?php foreach ($columns as $column): ?>
                                    <th><?= e(SizeGuide::header($column, $lang)) ?> (cm)</th>
                                <?php endforeach; ?>
                                <th>EU</th><th>IT</th><th>UK</th><th>US</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($guide['rows'] as $row): ?>
                                <tr>
                                    <th scope="row"><?= e((string) $row['label']) ?></th>
                                    <?php foreach ($columns as $column): ?>
                                        <td><?= e((string) $row[$column]) ?></td>
                                    <?php endforeach; ?>
                                    <td><?= e((string) $row['eu']) ?></td>
                                    <td><?= e((string) $row['it']) ?></td>
                                    <td><?= e((string) $row['uk']) ?></td>
                                    <td><?= e((string) $row['us']) ?></td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        <?php endforeach; ?>

        <p><a class="link-underline" href="<?= e(lang_url('size-guide')) ?>"><?= e(t('common.view')) ?></a></p>
    </div>
</div>

<?php if ($related !== []): ?>
<section class="section section--alt">
    <div class="wrap">
        <div class="section-head section-head--center">
            <h2><?= e(t('product.related')) ?></h2>
        </div>
        <div class="product-grid product-grid--pair">
            <?php foreach ($related as $item): ?>
                <?= View::partial('product-card', ['product' => $item]) ?>
            <?php endforeach; ?>
        </div>
    </div>
</section>
<?php endif; ?>
