<?php

use App\Core\View;

/** @var array $media */

View::share('pageTitle', 'Imágenes');
?>
<p class="page-intro">
    Tu biblioteca de fotografías. Sube aquí una imagen y copia su ruta para usarla en una
    página, en una entrada del Journal o en la portada.
</p>

<section class="card">
    <div class="card__head">
        <div><h2>Subir una imagen</h2><p>JPG, PNG, WebP o AVIF. Máximo 6 MB.</p></div>
    </div>

    <form action="<?= e(admin_url('media')) ?>" method="post" enctype="multipart/form-data">
        <?= csrf_field() ?>

        <div class="adm-grid">
            <div class="adm-field">
                <label for="image">Archivo</label>
                <input class="adm-input" type="file" id="image" name="image" accept="image/*" required
                       data-image-input="media-preview">
            </div>

            <div class="adm-field">
                <label for="alt">Descripción de la imagen</label>
                <input class="adm-input" type="text" id="alt" name="alt"
                       placeholder="Detalle del bordado floral">
                <p class="hint">Describe lo que se ve. Ayuda al posicionamiento y a la accesibilidad.</p>
            </div>
        </div>

        <img id="media-preview" alt="" hidden style="max-width:20rem;border-radius:.6rem;margin-bottom:1.6rem;">

        <button class="abtn" type="submit">Subir imagen</button>
    </form>
</section>

<section class="card" style="margin-top:2.4rem;">
    <div class="card__head">
        <div><h2><?= count($media) ?> imágenes subidas</h2><p>Pulsa «Copiar ruta» para pegarla donde la necesites.</p></div>
    </div>

    <?php if ($media === []): ?>
        <div class="empty">
            <h3>La biblioteca está vacía</h3>
            <p>Las fotografías de la colección inicial están en la carpeta del proyecto, no aquí.</p>
        </div>
    <?php else: ?>
        <div class="media-grid">
            <?php foreach ($media as $item): ?>
                <div class="media-item">
                    <img src="<?= e(img((string) $item['path'])) ?>" alt="<?= e((string) $item['alt']) ?>" loading="lazy">
                    <div class="media-item__body">
                        <code class="media-item__path"><?= e((string) $item['path']) ?></code>
                        <small style="color:var(--adm-faint);">
                            <?= (int) $item['width'] ?>&times;<?= (int) $item['height'] ?> ·
                            <?= number_format((int) $item['size'] / 1024, 0, ',', '.') ?> KB
                        </small>

                        <div style="display:flex;gap:.6rem;flex-wrap:wrap;margin-top:1rem;">
                            <button class="abtn abtn--ghost abtn--sm" type="button"
                                    data-copy="<?= e((string) $item['path']) ?>">Copiar ruta</button>

                            <form action="<?= e(admin_url('media/' . $item['id'] . '/delete')) ?>" method="post"
                                  data-confirm="Se borrará esta imagen del servidor. Si la estás usando en alguna página, dejará de verse."
                                  data-confirm-label="Sí, borrar">
                                <?= csrf_field() ?>
                                <button class="abtn abtn--danger abtn--sm" type="submit">Borrar</button>
                            </form>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>
</section>
