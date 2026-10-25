<?php

use App\Core\View;

/** @var array $products */
/** @var int $threshold */

View::share('pageTitle', 'Productos');

$statusTag = ['published' => ['tag--ok', 'A la venta'], 'draft' => ['tag--warn', 'Sin publicar'], 'archived' => ['tag--muted', 'Archivado']];
?>
<p class="page-intro">
    Todo lo que vendes. Desde aquí editas fotos, textos, precio y tallas de cada pieza.
</p>

<section class="card">
    <div class="card__head">
        <div>
            <h2><?= count($products) ?> productos</h2>
            <p>Las piezas sin publicar no se ven en la web.</p>
        </div>
        <div class="spacer"></div>
        <input class="adm-input adm-input--sm" type="search" style="max-width:24rem;"
               data-table-filter="products-table" placeholder="Buscar por nombre o referencia" aria-label="Buscar producto">
        <a class="abtn" href="<?= e(admin_url('products/new')) ?>">Nuevo producto</a>
    </div>

    <?php if ($products === []): ?>
        <div class="empty">
            <h3>Todavía no hay productos</h3>
            <p>Crea el primero para empezar a vender.</p>
            <a class="abtn" href="<?= e(admin_url('products/new')) ?>" style="margin-top:1.6rem;">Nuevo producto</a>
        </div>
    <?php else: ?>
        <div class="table-wrap">
            <table class="table" id="products-table">
                <thead>
                    <tr>
                        <th></th><th>Producto</th><th>Categoría</th><th class="num">Precio</th>
                        <th class="num">Existencias</th><th>Estado</th><th class="actions"></th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($products as $product): ?>
                        <?php
                        $stock = (int) $product['stock'];
                        [$tagClass, $tagLabel] = $statusTag[$product['status']] ?? ['tag--muted', $product['status']];
                        ?>
                        <tr>
                            <td>
                                <img class="table__media" src="<?= e(img((string) $product['image'])) ?>" alt="" loading="lazy">
                            </td>
                            <td>
                                <a href="<?= e(admin_url('products/' . $product['id'])) ?>" style="color:var(--adm-brand);">
                                    <strong><?= e((string) ($product['name'] ?? $product['slug'])) ?></strong>
                                </a><br>
                                <small style="color:var(--adm-faint);"><?= e((string) $product['sku']) ?></small>
                            </td>
                            <td><?= e((string) ($product['category_name'] ?? '—')) ?></td>
                            <td class="num"><?= money($product['price']) ?></td>
                            <td class="num">
                                <span class="tag <?= $stock === 0 ? 'tag--danger' : ($stock <= $threshold ? 'tag--warn' : 'tag--ok') ?>">
                                    <?= $stock ?>
                                </span>
                            </td>
                            <td><span class="tag <?= $tagClass ?>"><?= e($tagLabel) ?></span></td>
                            <td class="actions">
                                <a class="abtn abtn--ghost abtn--sm" href="<?= e(admin_url('products/' . $product['id'])) ?>">Editar</a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    <?php endif; ?>
</section>
