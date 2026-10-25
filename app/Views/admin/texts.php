<?php

use App\Core\View;

/** @var array $groups */
/** @var string $editLang */
/** @var array $labels */

View::share('pageTitle', 'Textos de la web');
?>
<p class="page-intro">
    Todas las palabras que aparecen en la tienda: botones, avisos, el texto de la portada.
    Cambia lo que quieras y guarda; se ve al instante en la web.
</p>

<section class="card">
    <div class="card__head">
        <div><h2>Idioma que estás editando</h2></div>
        <div class="spacer"></div>
        <div class="filters">
            <?php foreach ($langs as $code => $language): ?>
                <a href="<?= e(admin_url('texts?lang=' . $code)) ?>"
                   <?= $editLang === $code ? 'aria-current="page"' : '' ?>><?= e($language['native_name']) ?></a>
            <?php endforeach; ?>
        </div>
    </div>

    <input class="adm-input" type="search" data-table-filter="texts-table"
           placeholder="Buscar un texto" aria-label="Buscar un texto" style="max-width:32rem;">
</section>

<form action="<?= e(admin_url('texts')) ?>" method="post" data-dirty-guard>
    <?= csrf_field() ?>
    <input type="hidden" name="lang" value="<?= e($editLang) ?>">

    <?php foreach ($groups as $prefix => $strings): ?>
        <section class="card" style="margin-top:2.4rem;">
            <div class="card__head">
                <div><h2><?= e($labels[$prefix] ?? ucfirst((string) $prefix)) ?></h2></div>
            </div>

            <div class="table-wrap">
                <table class="table" id="texts-table">
                    <tbody>
                        <?php foreach ($strings as $string): ?>
                            <?php
                            $id     = (int) $string['id'];
                            $value  = (string) $string['value'];
                            $isLong = mb_strlen($value) > 90;
                            ?>
                            <tr>
                                <td style="width:26%;vertical-align:top;">
                                    <label for="text-<?= $id ?>" style="font-size:1.25rem;color:var(--adm-soft);">
                                        <?= e((string) $string['skey']) ?>
                                    </label>
                                </td>
                                <td>
                                    <?php if ($isLong): ?>
                                        <textarea class="adm-textarea" id="text-<?= $id ?>" name="text[<?= $id ?>]"
                                                  style="min-height:8rem;"><?= e($value) ?></textarea>
                                    <?php else: ?>
                                        <input class="adm-input" type="text" id="text-<?= $id ?>"
                                               name="text[<?= $id ?>]" value="<?= e($value) ?>">
                                    <?php endif; ?>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </section>
    <?php endforeach; ?>

    <div class="actions-bar">
        <span style="color:var(--adm-soft);font-size:1.3rem;">
            Si ves <code>%s</code> en un texto, déjalo: ahí se sustituye un importe o una cifra.
        </span>
        <div class="spacer"></div>
        <button class="abtn" type="submit">Guardar textos</button>
    </div>
</form>
