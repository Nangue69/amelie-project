<?php

use App\Core\Lang;
use App\Core\View;

/** @var array $page */
/** @var array $translations */

$title = (string) ($translations[Lang::default()]['title'] ?? $page['slug']);

View::share('pageTitle', $title);
?>
<p class="page-intro">
    <a href="<?= e(admin_url('pages')) ?>" style="color:var(--adm-brand);">&larr; Volver a páginas</a>
    &nbsp;·&nbsp;
    <a href="<?= e(lang_url((string) $page['slug'], Lang::default())) ?>" target="_blank" rel="noopener"
       style="color:var(--adm-brand);">Ver en la web</a>
</p>

<form action="<?= e(admin_url('pages/' . $page['id'])) ?>" method="post" data-dirty-guard>
    <?= csrf_field() ?>

    <section class="card">
        <div class="card__head">
            <div><h2>Visibilidad</h2></div>
            <div class="spacer"></div>
            <label class="switch">
                <input type="checkbox" name="is_active" value="1" <?= (int) $page['is_active'] === 1 ? 'checked' : '' ?>>
                <span class="switch__track"></span>
                <span>Publicada</span>
            </label>
        </div>

        <div class="adm-field" style="max-width:20rem;">
            <label for="position">Orden en el pie de página</label>
            <input class="adm-input" type="number" id="position" name="position" value="<?= (int) $page['position'] ?>">
        </div>
    </section>

    <section class="card" style="margin-top:2.4rem;">
        <div class="card__head">
            <div><h2>Contenido</h2><p>Un bloque por idioma.</p></div>
        </div>

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
                    <label for="t_<?= e($code) ?>_intro">Entradilla</label>
                    <input class="adm-input" type="text" id="t_<?= e($code) ?>_intro" name="t_<?= e($code) ?>_intro"
                           value="<?= e((string) ($translations[$code]['intro'] ?? '')) ?>">
                    <p class="hint">La frase que aparece bajo el título.</p>
                </div>

                <div class="adm-field">
                    <label for="t_<?= e($code) ?>_body">Texto de la página</label>
                    <textarea class="adm-textarea adm-textarea--tall" id="t_<?= e($code) ?>_body"
                              name="t_<?= e($code) ?>_body"><?= e((string) ($translations[$code]['body'] ?? '')) ?></textarea>
                    <p class="hint">Admite etiquetas sencillas: &lt;p&gt;, &lt;h2&gt;, &lt;ul&gt;, &lt;li&gt;, &lt;strong&gt;, &lt;a&gt;, &lt;table&gt;.</p>
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
        <a class="abtn abtn--ghost" href="<?= e(admin_url('pages')) ?>">Volver</a>
        <div class="spacer"></div>
        <button class="abtn" type="submit">Guardar cambios</button>
    </div>
</form>
