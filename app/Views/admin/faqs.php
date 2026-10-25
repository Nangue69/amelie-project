<?php

use App\Core\View;

/** @var array $faqs */

View::share('pageTitle', 'Preguntas frecuentes');
?>
<p class="page-intro">
    Las dudas que resolvemos antes de que la clienta tenga que escribir. Cada pregunta se
    traduce a los cuatro idiomas.
</p>

<form action="<?= e(admin_url('faq')) ?>" method="post" data-dirty-guard>
    <?= csrf_field() ?>

    <?php if ($faqs === []): ?>
        <section class="card">
            <div class="empty">
                <h3>Todavía no hay preguntas</h3>
                <p>Añade la primera con el botón de abajo.</p>
            </div>
        </section>
    <?php else: ?>
        <?php foreach ($faqs as $faq): ?>
            <?php $id = (int) $faq['id']; ?>
            <section class="card" style="margin-bottom:2.4rem;">
                <div class="card__head">
                    <div><h2 style="font-size:1.6rem;"><?= e((string) ($faq['question'] ?? 'Pregunta')) ?></h2></div>
                    <div class="spacer"></div>

                    <div class="adm-field" style="margin:0;max-width:9rem;">
                        <label class="visually-hidden" for="faq-pos-<?= $id ?>">Orden</label>
                        <input class="adm-input adm-input--sm adm-input--num" type="number" id="faq-pos-<?= $id ?>"
                               name="faq[<?= $id ?>][position]" value="<?= (int) $faq['position'] ?>">
                    </div>

                    <label class="switch">
                        <input type="checkbox" name="faq[<?= $id ?>][is_active]" value="1"
                               <?= (int) $faq['is_active'] === 1 ? 'checked' : '' ?>>
                        <span class="switch__track"></span>
                        <span>Visible</span>
                    </label>
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
                            <label for="faq-q-<?= $id ?>-<?= e($code) ?>">Pregunta</label>
                            <input class="adm-input" type="text" id="faq-q-<?= $id ?>-<?= e($code) ?>"
                                   name="faq[<?= $id ?>][question][<?= e($code) ?>]"
                                   value="<?= e((string) ($faq['translations'][$code]['question'] ?? '')) ?>">
                        </div>

                        <div class="adm-field">
                            <label for="faq-a-<?= $id ?>-<?= e($code) ?>">Respuesta</label>
                            <textarea class="adm-textarea" id="faq-a-<?= $id ?>-<?= e($code) ?>"
                                      name="faq[<?= $id ?>][answer][<?= e($code) ?>]"><?= e((string) ($faq['translations'][$code]['answer'] ?? '')) ?></textarea>
                        </div>
                    </div>
                <?php $first = false; endforeach; ?>
            </section>
        <?php endforeach; ?>
    <?php endif; ?>

    <div class="actions-bar">
        <div class="spacer"></div>
        <button class="abtn" type="submit">Guardar preguntas</button>
    </div>
</form>

<div style="display:flex;gap:.8rem;flex-wrap:wrap;margin-top:2.4rem;">
    <form action="<?= e(admin_url('faq/new')) ?>" method="post">
        <?= csrf_field() ?>
        <button class="abtn abtn--ghost" type="submit">Añadir una pregunta</button>
    </form>

    <?php foreach ($faqs as $faq): ?>
        <form action="<?= e(admin_url('faq/' . $faq['id'] . '/delete')) ?>" method="post"
              data-confirm="Se eliminará la pregunta «<?= e((string) ($faq['question'] ?? '')) ?>». ¿Continuamos?"
              data-confirm-label="Sí, eliminar">
            <?= csrf_field() ?>
            <button class="abtn abtn--danger abtn--sm" type="submit">
                Eliminar: <?= e(excerpt((string) ($faq['question'] ?? ''), 36)) ?>
            </button>
        </form>
    <?php endforeach; ?>
</div>
