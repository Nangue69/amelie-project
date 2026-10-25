<?php

use App\Core\Lang;
use App\Core\View;

/** @var array $product */
/** @var array $translations */
/** @var array $images */
/** @var array $variants */
/** @var array $categories */

$name = (string) ($translations[Lang::default()]['name'] ?? $product['slug']);

View::share('pageTitle', $name);

$isPair = $product['size_mode'] === 'bra_brief';

$fields = [
    'name'              => ['Nombre', 'input'],
    'tagline'           => ['Lema corto', 'input'],
    'short_description' => ['Descripción breve', 'textarea'],
    'description'       => ['Descripción completa', 'html'],
    'composition'       => ['Composición', 'html'],
    'care'              => ['Cuidados', 'html'],
    'shipping_returns'  => ['Envíos y devoluciones', 'html'],
    'meta_title'        => ['Título para Google', 'input'],
    'meta_description'  => ['Descripción para Google', 'textarea'],
];

$kinds = [
    'model'     => 'Con modelo',
    'product'   => 'Producto solo',
    'detail'    => 'Detalle',
    'packaging' => 'Packaging',
];
?>
<p class="page-intro">
    <a href="<?= e(admin_url('products')) ?>" style="color:var(--adm-brand);">&larr; Volver a productos</a>
    &nbsp;·&nbsp;
    <a href="<?= e(lang_url('product/' . $product['slug'], Lang::default())) ?>" target="_blank" rel="noopener"
       style="color:var(--adm-brand);">Ver en la web</a>
</p>

<form action="<?= e(admin_url('products/' . $product['id'])) ?>" method="post" data-dirty-guard>
    <?= csrf_field() ?>

    <section class="card">
        <div class="card__head">
            <div><h2>Datos de venta</h2><p>Precio, categoría y visibilidad.</p></div>
        </div>

        <div class="adm-grid">
            <div class="adm-field">
                <label for="slug">Enlace en la web</label>
                <input class="adm-input" type="text" id="slug" name="slug" value="<?= e((string) $product['slug']) ?>">
            </div>

            <div class="adm-field">
                <label for="sku">Referencia interna</label>
                <input class="adm-input" type="text" id="sku" name="sku" value="<?= e((string) $product['sku']) ?>">
            </div>

            <div class="adm-field">
                <label for="price">Precio de venta</label>
                <input class="adm-input" type="text" id="price" name="price" inputmode="decimal"
                       value="<?= e(number_format((float) $product['price'], 2, ',', '')) ?>">
            </div>

            <div class="adm-field">
                <label for="compare_price">Precio anterior (opcional)</label>
                <input class="adm-input" type="text" id="compare_price" name="compare_price" inputmode="decimal"
                       value="<?= $product['compare_price'] === null ? '' : e(number_format((float) $product['compare_price'], 2, ',', '')) ?>">
                <p class="hint">Si lo rellenas, se muestra tachado junto al precio actual.</p>
            </div>

            <div class="adm-field">
                <label for="category_id">Categoría</label>
                <select class="adm-select" id="category_id" name="category_id">
                    <option value="">Sin categoría</option>
                    <?php foreach ($categories as $category): ?>
                        <option value="<?= (int) $category['id'] ?>"
                            <?= (int) $product['category_id'] === (int) $category['id'] ? 'selected' : '' ?>>
                            <?= e((string) ($category['name'] ?? $category['slug'])) ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div class="adm-field">
                <label for="status">Estado</label>
                <select class="adm-select" id="status" name="status">
                    <option value="draft" <?= $product['status'] === 'draft' ? 'selected' : '' ?>>Sin publicar</option>
                    <option value="published" <?= $product['status'] === 'published' ? 'selected' : '' ?>>A la venta</option>
                    <option value="archived" <?= $product['status'] === 'archived' ? 'selected' : '' ?>>Archivado</option>
                </select>
            </div>

            <div class="adm-field">
                <label for="size_mode">Cómo se eligen las tallas</label>
                <select class="adm-select" id="size_mode" name="size_mode">
                    <option value="single" <?= !$isPair ? 'selected' : '' ?>>Una sola talla</option>
                    <option value="bra_brief" <?= $isPair ? 'selected' : '' ?>>Dos tallas: sujetador y tanga</option>
                </select>
            </div>

            <div class="adm-field">
                <label for="position">Orden en la tienda</label>
                <input class="adm-input" type="number" id="position" name="position" value="<?= (int) $product['position'] ?>">
            </div>

            <div class="adm-field">
                <label>Destacado</label>
                <label class="switch">
                    <input type="checkbox" name="is_featured" value="1" <?= (int) $product['is_featured'] === 1 ? 'checked' : '' ?>>
                    <span class="switch__track"></span>
                    <span>Mostrar en la página de inicio</span>
                </label>
            </div>
        </div>
    </section>

    <section class="card" style="margin-top:2.4rem;">
        <div class="card__head">
            <div><h2>Textos</h2><p>Un bloque por idioma. Lo que escribas aquí es lo que lee la clienta.</p></div>
        </div>

        <div class="tabs" role="tablist" data-tabs>
            <?php $first = true; foreach ($langs as $code => $language): ?>
                <button type="button" role="tab" data-tab="<?= e($code) ?>"
                        aria-selected="<?= $first ? 'true' : 'false' ?>"><?= e($language['native_name']) ?></button>
            <?php $first = false; endforeach; ?>
        </div>

        <?php $first = true; foreach ($langs as $code => $language): ?>
            <div class="tab-panel" data-tab-panel="<?= e($code) ?>" <?= $first ? '' : 'hidden' ?>>
                <?php foreach ($fields as $field => [$label, $type]): ?>
                    <?php $value = (string) ($translations[$code][$field] ?? ''); ?>
                    <div class="adm-field">
                        <label for="t_<?= e($code . '_' . $field) ?>"><?= e($label) ?></label>
                        <?php if ($type === 'input'): ?>
                            <input class="adm-input" type="text" id="t_<?= e($code . '_' . $field) ?>"
                                   name="t_<?= e($code . '_' . $field) ?>" value="<?= e($value) ?>">
                        <?php elseif ($type === 'textarea'): ?>
                            <textarea class="adm-textarea" id="t_<?= e($code . '_' . $field) ?>"
                                      name="t_<?= e($code . '_' . $field) ?>" style="min-height:9rem;"><?= e($value) ?></textarea>
                        <?php else: ?>
                            <textarea class="adm-textarea adm-textarea--tall" id="t_<?= e($code . '_' . $field) ?>"
                                      name="t_<?= e($code . '_' . $field) ?>"><?= e($value) ?></textarea>
                            <p class="hint">Admite etiquetas sencillas: &lt;p&gt;, &lt;strong&gt;, &lt;ul&gt;, &lt;li&gt;, &lt;h2&gt;.</p>
                        <?php endif; ?>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php $first = false; endforeach; ?>
    </section>

    <div class="actions-bar">
        <a class="abtn abtn--ghost" href="<?= e(admin_url('products')) ?>">Volver</a>
        <div class="spacer"></div>
        <button class="abtn" type="submit">Guardar cambios</button>
    </div>
</form>

<section class="card" style="margin-top:2.4rem;">
    <div class="card__head">
        <div><h2>Tallas y existencias</h2><p>Cada fila es una talla que la clienta puede comprar.</p></div>
    </div>

    <form action="<?= e(admin_url('products/' . $product['id'] . '/variants')) ?>" method="post">
        <?= csrf_field() ?>

        <?php if ($variants === []): ?>
            <div class="empty"><p>Todavía no has añadido ninguna talla.</p></div>
        <?php else: ?>
            <div class="table-wrap">
                <table class="table">
                    <thead>
                        <tr>
                            <?php if ($isPair): ?>
                                <th>Sujetador</th><th>Tanga</th>
                            <?php else: ?>
                                <th>Talla</th>
                            <?php endif; ?>
                            <th>Referencia</th><th class="num">Existencias</th>
                            <th class="num">Precio propio</th><th>Activa</th><th>Orden</th><th class="actions"></th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($variants as $variant): ?>
                            <?php $vid = (int) $variant['id']; ?>
                            <tr>
                                <?php if ($isPair): ?>
                                    <td><input class="adm-input adm-input--sm" style="max-width:9rem;" type="text"
                                               name="variant[<?= $vid ?>][size_bra]" value="<?= e((string) $variant['size_bra']) ?>"
                                               aria-label="Talla de sujetador"></td>
                                    <td><input class="adm-input adm-input--sm" style="max-width:9rem;" type="text"
                                               name="variant[<?= $vid ?>][size_brief]" value="<?= e((string) $variant['size_brief']) ?>"
                                               aria-label="Talla de tanga"></td>
                                <?php else: ?>
                                    <td><input class="adm-input adm-input--sm" style="max-width:9rem;" type="text"
                                               name="variant[<?= $vid ?>][size]" value="<?= e((string) $variant['size']) ?>"
                                               aria-label="Talla"></td>
                                <?php endif; ?>

                                <td><input class="adm-input adm-input--sm" type="text"
                                           name="variant[<?= $vid ?>][sku]" value="<?= e((string) $variant['sku']) ?>"
                                           aria-label="Referencia"></td>

                                <td class="num"><input class="adm-input adm-input--sm adm-input--num" type="number" min="0"
                                           name="variant[<?= $vid ?>][stock]" value="<?= (int) $variant['stock'] ?>"
                                           aria-label="Existencias"></td>

                                <td class="num"><input class="adm-input adm-input--sm adm-input--num" type="text" inputmode="decimal"
                                           name="variant[<?= $vid ?>][price_override]"
                                           value="<?= $variant['price_override'] === null ? '' : e(number_format((float) $variant['price_override'], 2, ',', '')) ?>"
                                           aria-label="Precio propio"></td>

                                <td>
                                    <label class="check">
                                        <input type="checkbox" name="variant[<?= $vid ?>][is_active]" value="1"
                                               <?= (int) $variant['is_active'] === 1 ? 'checked' : '' ?>>
                                        <span class="visually-hidden">Activa</span>
                                    </label>
                                </td>

                                <td><input class="adm-input adm-input--sm adm-input--num" type="number"
                                           name="variant[<?= $vid ?>][position]" value="<?= (int) $variant['position'] ?>"
                                           aria-label="Orden"></td>

                                <td class="actions"></td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>

            <div style="margin-top:1.6rem;">
                <button class="abtn" type="submit">Guardar tallas y existencias</button>
            </div>
        <?php endif; ?>
    </form>

    <?php if ($variants !== []): ?>
        <div class="table-wrap" style="margin-top:1.6rem;">
            <table class="table">
                <tbody>
                    <?php foreach ($variants as $variant): ?>
                        <tr>
                            <td>
                                <?= $isPair
                                    ? e($variant['size_bra'] . ' / ' . $variant['size_brief'])
                                    : e((string) $variant['size']) ?>
                            </td>
                            <td class="actions">
                                <form action="<?= e(admin_url('products/' . $product['id'] . '/variants/' . $variant['id'] . '/delete')) ?>"
                                      method="post" data-confirm="Se eliminará esta talla del catálogo. ¿Continuamos?"
                                      data-confirm-label="Sí, eliminar" style="display:inline;">
                                    <?= csrf_field() ?>
                                    <button class="abtn abtn--danger abtn--sm" type="submit">Eliminar talla</button>
                                </form>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    <?php endif; ?>

    <form action="<?= e(admin_url('products/' . $product['id'] . '/variants/new')) ?>" method="post"
          style="margin-top:2.4rem;padding-top:2.4rem;border-top:1px solid var(--adm-line);">
        <?= csrf_field() ?>
        <h3 style="font-size:1.6rem;">Añadir una talla</h3>

        <div class="adm-grid">
            <?php if ($isPair): ?>
                <div class="adm-field">
                    <label for="new_size_bra">Talla de sujetador</label>
                    <input class="adm-input" type="text" id="new_size_bra" name="size_bra" placeholder="75B">
                </div>
                <div class="adm-field">
                    <label for="new_size_brief">Talla de tanga</label>
                    <input class="adm-input" type="text" id="new_size_brief" name="size_brief" placeholder="S">
                </div>
            <?php else: ?>
                <div class="adm-field">
                    <label for="new_size">Talla</label>
                    <input class="adm-input" type="text" id="new_size" name="size" placeholder="M">
                </div>
            <?php endif; ?>

            <div class="adm-field">
                <label for="new_sku">Referencia</label>
                <input class="adm-input" type="text" id="new_sku" name="sku">
            </div>

            <div class="adm-field">
                <label for="new_stock">Existencias iniciales</label>
                <input class="adm-input" type="number" id="new_stock" name="stock" min="0" value="0">
            </div>
        </div>

        <button class="abtn abtn--ghost" type="submit">Añadir talla</button>
    </form>
</section>

<section class="card" style="margin-top:2.4rem;">
    <div class="card__head">
        <div><h2>Fotografías</h2><p>La primera es la que se ve en el listado de la tienda.</p></div>
    </div>

    <?php if ($images === []): ?>
        <div class="empty"><p>Este producto todavía no tiene fotografías.</p></div>
    <?php else: ?>
        <div class="media-grid">
            <?php foreach ($images as $image): ?>
                <div class="media-item">
                    <img src="<?= e(img((string) $image['path'])) ?>" alt="<?= e((string) $image['alt']) ?>" loading="lazy">
                    <div class="media-item__body">
                        <span class="tag <?= (int) $image['is_primary'] === 1 ? 'tag--ok' : 'tag--muted' ?>">
                            <?= (int) $image['is_primary'] === 1 ? 'Principal' : ($kinds[$image['kind']] ?? $image['kind']) ?>
                        </span>

                        <div style="display:flex;gap:.6rem;flex-wrap:wrap;margin-top:1rem;">
                            <?php if ((int) $image['is_primary'] !== 1): ?>
                                <form action="<?= e(admin_url('products/' . $product['id'] . '/images/' . $image['id'] . '/primary')) ?>" method="post">
                                    <?= csrf_field() ?>
                                    <button class="abtn abtn--ghost abtn--sm" type="submit">Hacer principal</button>
                                </form>
                            <?php endif; ?>

                            <form action="<?= e(admin_url('products/' . $product['id'] . '/images/' . $image['id'] . '/delete')) ?>"
                                  method="post" data-confirm="Se quitará esta fotografía del producto. ¿Continuamos?"
                                  data-confirm-label="Sí, quitar">
                                <?= csrf_field() ?>
                                <button class="abtn abtn--danger abtn--sm" type="submit">Quitar</button>
                            </form>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>

    <form action="<?= e(admin_url('products/' . $product['id'] . '/images')) ?>" method="post"
          enctype="multipart/form-data"
          style="margin-top:2.4rem;padding-top:2.4rem;border-top:1px solid var(--adm-line);">
        <?= csrf_field() ?>
        <h3 style="font-size:1.6rem;">Añadir una fotografía</h3>

        <div class="adm-grid">
            <div class="adm-field">
                <label for="image">Archivo</label>
                <input class="adm-input" type="file" id="image" name="image" accept="image/*" required
                       data-image-input="image-preview">
                <p class="hint">JPG, PNG, WebP o AVIF. Máximo 6 MB.</p>
            </div>

            <div class="adm-field">
                <label for="alt">Descripción de la imagen</label>
                <input class="adm-input" type="text" id="alt" name="alt"
                       placeholder="Conjunto Blush Floral visto de frente">
                <p class="hint">Ayuda a Google y a quien navega con lector de pantalla.</p>
            </div>

            <div class="adm-field">
                <label for="kind">Tipo de fotografía</label>
                <select class="adm-select" id="kind" name="kind">
                    <?php foreach ($kinds as $value => $label): ?>
                        <option value="<?= e($value) ?>"><?= e($label) ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
        </div>

        <img id="image-preview" alt="" hidden
             style="max-width:20rem;border-radius:.6rem;margin-bottom:1.6rem;">

        <button class="abtn abtn--ghost" type="submit">Subir fotografía</button>
    </form>
</section>

<section class="card" style="margin-top:2.4rem;border-color:#E3C9C9;">
    <div class="card__head" style="border-bottom-color:#E3C9C9;">
        <div>
            <h2 style="color:var(--adm-danger);">Eliminar producto</h2>
            <p>Si tiene pedidos asociados se archivará en lugar de borrarse, para no romper el historial.</p>
        </div>
    </div>

    <form action="<?= e(admin_url('products/' . $product['id'] . '/delete')) ?>" method="post"
          data-confirm="Vas a eliminar este producto de la tienda. Esta acción no se puede deshacer."
          data-confirm-label="Sí, eliminar">
        <?= csrf_field() ?>
        <button class="abtn abtn--danger" type="submit">Eliminar este producto</button>
    </form>
</section>
