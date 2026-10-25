<?php

use App\Core\View;

/** @var array $categories */

View::share('pageTitle', 'Nuevo producto');
?>
<p class="page-intro">
    Empieza con lo básico. Después podrás añadir fotos, tallas y los textos en cada idioma.
</p>

<form class="card" action="<?= e(admin_url('products/new')) ?>" method="post" style="max-width:76rem;">
    <?= csrf_field() ?>

    <div class="adm-grid">
        <div class="adm-field">
            <label for="name">Nombre del producto</label>
            <input class="adm-input" type="text" id="name" name="name" required data-slug-source
                   placeholder="Por ejemplo: Blush Floral">
        </div>

        <div class="adm-field">
            <label for="slug">Enlace en la web</label>
            <input class="adm-input" type="text" id="slug" name="slug" data-slug-target placeholder="blush-floral">
            <p class="hint">Se genera solo a partir del nombre. Se usa en la dirección de la página.</p>
        </div>

        <div class="adm-field">
            <label for="sku">Referencia interna</label>
            <input class="adm-input" type="text" id="sku" name="sku" placeholder="AM-BF-01">
        </div>

        <div class="adm-field">
            <label for="price">Precio de venta</label>
            <input class="adm-input" type="text" id="price" name="price" inputmode="decimal" placeholder="189,00">
        </div>

        <div class="adm-field">
            <label for="category_id">Categoría</label>
            <select class="adm-select" id="category_id" name="category_id">
                <option value="">Sin categoría</option>
                <?php foreach ($categories as $category): ?>
                    <option value="<?= (int) $category['id'] ?>"><?= e((string) ($category['name'] ?? $category['slug'])) ?></option>
                <?php endforeach; ?>
            </select>
        </div>

        <div class="adm-field">
            <label for="size_mode">Cómo se eligen las tallas</label>
            <select class="adm-select" id="size_mode" name="size_mode">
                <option value="single">Una sola talla (corsés, bodies)</option>
                <option value="bra_brief">Dos tallas: sujetador y tanga (conjuntos)</option>
            </select>
            <p class="hint">Elige «dos tallas» si la clienta debe poder combinar copa y braguita por separado.</p>
        </div>
    </div>

    <div class="actions-bar">
        <a class="abtn abtn--ghost" href="<?= e(admin_url('products')) ?>">Cancelar</a>
        <div class="spacer"></div>
        <button class="abtn" type="submit">Crear y continuar</button>
    </div>
</form>
