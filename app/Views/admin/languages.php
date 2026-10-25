<?php

use App\Core\Settings;
use App\Core\View;

/** @var array $languages */
/** @var array $counts */

View::share('pageTitle', 'Idiomas');

$current = Settings::get('default_lang', 'es');
?>
<p class="page-intro">
    En qué idiomas está disponible la tienda. El idioma principal es el que ve quien entra
    sin elegir ninguno; no se puede desactivar.
</p>

<form action="<?= e(admin_url('languages')) ?>" method="post" data-dirty-guard>
    <?= csrf_field() ?>

    <section class="card">
        <div class="table-wrap">
            <table class="table">
                <thead>
                    <tr>
                        <th>Idioma</th><th>Código</th><th>Nombre que se muestra</th>
                        <th class="num">Textos traducidos</th><th>Activo</th><th>Principal</th><th class="num">Orden</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($languages as $language): ?>
                        <?php
                        $code      = (string) $language['code'];
                        $isDefault = $code === $current;
                        ?>
                        <tr>
                            <td><strong><?= e((string) $language['name']) ?></strong></td>
                            <td style="color:var(--adm-faint);"><?= e(strtoupper($code)) ?></td>

                            <td>
                                <label class="visually-hidden" for="lang-name-<?= e($code) ?>">Nombre que se muestra</label>
                                <input class="adm-input adm-input--sm" type="text" id="lang-name-<?= e($code) ?>"
                                       name="language[<?= e($code) ?>][native_name]"
                                       value="<?= e((string) $language['native_name']) ?>">
                            </td>

                            <td class="num">
                                <span class="tag <?= ($counts[$code] ?? 0) > 0 ? 'tag--ok' : 'tag--warn' ?>">
                                    <?= (int) ($counts[$code] ?? 0) ?>
                                </span>
                            </td>

                            <td>
                                <label class="check">
                                    <input type="checkbox" name="language[<?= e($code) ?>][is_active]" value="1"
                                           <?= (int) $language['is_active'] === 1 ? 'checked' : '' ?>
                                           <?= $isDefault ? 'checked disabled' : '' ?>>
                                    <span class="visually-hidden">Activo</span>
                                </label>
                            </td>

                            <td>
                                <label class="check">
                                    <input type="radio" name="default_lang" value="<?= e($code) ?>"
                                           <?= $isDefault ? 'checked' : '' ?>>
                                    <span class="visually-hidden">Idioma principal</span>
                                </label>
                            </td>

                            <td class="num">
                                <label class="visually-hidden" for="lang-pos-<?= e($code) ?>">Orden</label>
                                <input class="adm-input adm-input--sm adm-input--num" type="number"
                                       id="lang-pos-<?= e($code) ?>" name="language[<?= e($code) ?>][position]"
                                       value="<?= (int) $language['position'] ?>">
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </section>

    <div class="actions-bar">
        <span style="color:var(--adm-soft);font-size:1.3rem;">
            Los textos de cada idioma se editan en
            <a href="<?= e(admin_url('texts')) ?>" style="color:var(--adm-brand);">Textos de la web</a>.
        </span>
        <div class="spacer"></div>
        <button class="abtn" type="submit">Guardar idiomas</button>
    </div>
</form>
