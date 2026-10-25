<?php

use App\Core\Lang;
use App\Core\View;

/** @var array $post */
/** @var array $translations */

$title = (string) ($translations[Lang::default()]['title'] ?? $post['slug']);

View::share('pageTitle', $title);

$publishedAt = $post['published_at'] === null
    ? date('Y-m-d\TH:i')
    : date('Y-m-d\TH:i', (int) strtotime((string) $post['published_at']));
?>
<p class="page-intro">
    <a href="<?= e(admin_url('posts')) ?>" style="color:var(--adm-brand);">&larr; Volver al Journal</a>
    <?php if ($post['status'] === 'published'): ?>
        &nbsp;·&nbsp;
        <a href="<?= e(lang_url('journal/' . $post['slug'], Lang::default())) ?>" target="_blank" rel="noopener"
           style="color:var(--adm-brand);">Ver en la web</a>
    <?php endif; ?>
</p>

<form action="<?= e(admin_url('posts/' . $post['id'])) ?>" method="post" data-dirty-guard>
    <?= csrf_field() ?>

    <section class="card">
        <div class="card__head"><div><h2>Publicación</h2></div></div>

        <div class="adm-grid">
            <div class="adm-field">
                <label for="slug">Enlace en la web</label>
                <input class="adm-input" type="text" id="slug" name="slug" value="<?= e((string) $post['slug']) ?>">
            </div>

            <div class="adm-field">
                <label for="status">Estado</label>
                <select class="adm-select" id="status" name="status">
                    <option value="draft" <?= $post['status'] === 'draft' ? 'selected' : '' ?>>Borrador</option>
                    <option value="published" <?= $post['status'] === 'published' ? 'selected' : '' ?>>Publicada</option>
                </select>
            </div>

            <div class="adm-field">
                <label for="published_at">Fecha de publicación</label>
                <input class="adm-input" type="datetime-local" id="published_at" name="published_at"
                       value="<?= e($publishedAt) ?>">
            </div>

            <div class="adm-field">
                <label for="cover_image">Fotografía de portada</label>
                <input class="adm-input" type="text" id="cover_image" name="cover_image"
                       value="<?= e((string) $post['cover_image']) ?>">
                <p class="hint">Copia la ruta desde la sección «Imágenes».</p>
            </div>
        </div>

        <?php if ($post['cover_image'] !== ''): ?>
            <img src="<?= e(img((string) $post['cover_image'])) ?>" alt="" loading="lazy"
                 style="max-width:28rem;border-radius:.6rem;margin-top:.8rem;">
        <?php endif; ?>
    </section>

    <section class="card" style="margin-top:2.4rem;">
        <div class="card__head"><div><h2>Texto</h2><p>Un bloque por idioma.</p></div></div>

        <div class="tabs" role="tablist" data-tabs>
            <?php $first = true; foreach ($langs as $code => $language): ?>
                <button type="button" role="tab" data-tab="<?= e($code) ?>"
                        aria-selected="<?= $first ? 'true' : 'false' ?>"><?= e($language['native_name']) ?></button>
            <?php $first = false; endforeach; ?>
        </div>

        <?php $first = true; foreach ($langs as $code => $language): ?>
            <div class="tab-panel" data-tab-panel="<?= e($code) ?>" <?= $first ? '' : 'hidden' ?>>
                <div class="adm-field">
                    <label for="t_<?= e($code) ?>_title">Título</label>
                    <input class="adm-input" type="text" id="t_<?= e($code) ?>_title" name="t_<?= e($code) ?>_title"
                           value="<?= e((string) ($translations[$code]['title'] ?? '')) ?>">
                </div>

                <div class="adm-field">
                    <label for="t_<?= e($code) ?>_excerpt">Resumen</label>
                    <textarea class="adm-textarea" id="t_<?= e($code) ?>_excerpt" name="t_<?= e($code) ?>_excerpt"
                              style="min-height:9rem;"><?= e((string) ($translations[$code]['excerpt'] ?? '')) ?></textarea>
                    <p class="hint">Es lo que se lee en el listado del Journal.</p>
                </div>

                <div class="adm-field">
                    <label for="t_<?= e($code) ?>_body">Contenido</label>
                    <textarea class="adm-textarea adm-textarea--tall" id="t_<?= e($code) ?>_body"
                              name="t_<?= e($code) ?>_body"><?= e((string) ($translations[$code]['body'] ?? '')) ?></textarea>
                </div>

                <div class="adm-grid">
                    <div class="adm-field">
                        <label for="t_<?= e($code) ?>_meta_title">Título para Google</label>
                        <input class="adm-input" type="text" id="t_<?= e($code) ?>_meta_title"
                               name="t_<?= e($code) ?>_meta_title"
                               value="<?= e((string) ($translations[$code]['meta_title'] ?? '')) ?>">
                    </div>

                    <div class="adm-field">
                        <label for="t_<?= e($code) ?>_meta_description">Descripción para Google</label>
                        <input class="adm-input" type="text" id="t_<?= e($code) ?>_meta_description"
                               name="t_<?= e($code) ?>_meta_description"
                               value="<?= e((string) ($translations[$code]['meta_description'] ?? '')) ?>">
                    </div>
                </div>
            </div>
        <?php $first = false; endforeach; ?>
    </section>

    <div class="actions-bar">
        <a class="abtn abtn--ghost" href="<?= e(admin_url('posts')) ?>">Volver</a>
        <div class="spacer"></div>
        <button class="abtn" type="submit">Guardar cambios</button>
    </div>
</form>

<section class="card" style="margin-top:2.4rem;border-color:#E3C9C9;">
    <div class="card__head" style="border-bottom-color:#E3C9C9;">
        <div><h2 style="color:var(--adm-danger);">Eliminar entrada</h2></div>
    </div>

    <form action="<?= e(admin_url('posts/' . $post['id'] . '/delete')) ?>" method="post"
          data-confirm="Vas a eliminar esta entrada del Journal. Esta acción no se puede deshacer."
          data-confirm-label="Sí, eliminar">
        <?= csrf_field() ?>
        <button class="abtn abtn--danger" type="submit">Eliminar esta entrada</button>
    </form>
</section>
