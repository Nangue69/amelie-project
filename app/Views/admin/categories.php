<?php

use App\Core\View;

/** @var array $categories */

View::share('pageTitle', 'Categorías');
?>
<p class="page-intro">
    Los grupos por los que se filtra la tienda. El nombre y la descripción se traducen a cada idioma.
</p>

<form action="<?= e(admin_url('categories')) ?>" method="post" data-dirty-guard>
    <?= csrf_field() ?>

    <?php foreach ($categories as $category): ?>
        <?php $id = (int) $category['id']; ?>
        <section class="card" style="margin-bottom:2.4rem;">
            <div class="card__head">
                <div>
                    <h2><?= e((string) ($category['name'] ?? $category['slug'])) ?></h2>
                    <p>/<?= e((string) $category['slug']) ?></p>
                </div>
                <div class="spacer"></div>
                <label class="switch">
                    <input type="checkbox" name="category[<?= $id ?>][is_active]" value="1"
                           <?= (int) $category['is_active'] === 1 ? 'checked' : '' ?>>
                    <span class="switch__track"></span>
                    <span>Visible</span>
                </label>
            </div>

            <div class="adm-grid">
                <div class="adm-field">
                    <label for="cat-img-<?= $id ?>">Imagen</label>
                    <input class="adm-input" type="text" id="cat-img-<?= $id ?>"
                           name="category[<?= $id ?>][image]" value="<?= e((string) $category['image']) ?>">
                    <p class="hint">Ruta de una imagen de la biblioteca.</p>
                </div>

                <div class="adm-field">
                    <label for="cat-pos-<?= $id ?>">Orden</label>
                    <input class="adm-input" type="number" id="cat-pos-<?= $id ?>"
                           name="category[<?= $id ?>][position]" value="<?= (int) $category['position'] ?>">
                </div>
            </div>

            <div class="tabs" role="tablist" data-tabs>
                <?php $first = true; foreach ($langs as $code => $language): ?>
                    <button type="button" role="tab" data-tab="<?= e($code . '-' . $id) ?>"
                            aria-selected="<?= $first ? 'true' : 'false' ?>"><?= e($language['native_name']) ?></button>
                <?php $first = false; endforeach; ?>
            </div>

            <?php $first = true; foreach ($langs as $code => $language): ?>
                <div class="tab-panel" data-tab-panel="<?= e($code . '-' . $id) ?>" <?= $first ? '' : 'hidden' ?>>
                    <div class="adm-field">
                        <label for="cat-name-<?= $id ?>-<?= e($code) ?>">Nombre</label>
                        <input class="adm-input" type="text" id="cat-name-<?= $id ?>-<?= e($code) ?>"
                               name="category[<?= $id ?>][name][<?= e($code) ?>]"
                               value="<?= e((string) ($category['translations'][$code]['name'] ?? '')) ?>">
                    </div>

                    <div class="adm-field">
                        <label for="cat-desc-<?= $id ?>-<?= e($code) ?>">Descripción</label>
                        <textarea class="adm-textarea" id="cat-desc-<?= $id ?>-<?= e($code) ?>" style="min-height:9rem;"
                                  name="category[<?= $id ?>][description][<?= e($code) ?>]"><?= e((string) ($category['translations'][$code]['description'] ?? '')) ?></textarea>
                    </div>

                    <div class="adm-grid">
                        <div class="adm-field">
                            <label for="cat-mt-<?= $id ?>-<?= e($code) ?>">Título para Google</label>
                            <input class="adm-input" type="text" id="cat-mt-<?= $id ?>-<?= e($code) ?>"
                                   name="category[<?= $id ?>][meta_title][<?= e($code) ?>]"
                                   value="<?= e((string) ($category['translations'][$code]['meta_title'] ?? '')) ?>">
                        </div>

                        <div class="adm-field">
                            <label for="cat-md-<?= $id ?>-<?= e($code) ?>">Descripción para Google</label>
                            <input class="adm-input" type="text" id="cat-md-<?= $id ?>-<?= e($code) ?>"
                                   name="category[<?= $id ?>][meta_description][<?= e($code) ?>]"
                                   value="<?= e((string) ($category['translations'][$code]['meta_description'] ?? '')) ?>">
                        </div>
                    </div>
                </div>
            <?php $first = false; endforeach; ?>
        </section>
    <?php endforeach; ?>

    <div class="actions-bar">
        <div class="spacer"></div>
        <button class="abtn" type="submit">Guardar categorías</button>
    </div>
</form>
