<?php

use App\Core\Lang;
use App\Core\View;
use App\Models\SizeGuide;

/** @var array $guides */

View::share('pageTitle', 'Guía de tallas');

$lang    = Lang::default();
$columns = ['bust', 'underbust', 'waist', 'hip'];
?>
<p class="page-intro">
    Las tablas de medidas que ve la clienta en la ficha de producto y en la página de guía
    de tallas. Las medidas van en centímetros; deja en blanco lo que no apliques.
</p>

<form action="<?= e(admin_url('sizes')) ?>" method="post" data-dirty-guard>
    <?= csrf_field() ?>

    <?php foreach ($guides as $guide): ?>
        <section class="card" style="margin-bottom:2.4rem;">
            <div class="card__head">
                <div><h2><?= e(SizeGuide::title((string) $guide['gkey'], $lang)) ?></h2></div>
            </div>

            <div class="table-wrap">
                <table class="table">
                    <thead>
                        <tr>
                            <th>Talla</th>
                            <?php foreach ($columns as $column): ?>
                                <th><?= e(SizeGuide::header($column, $lang)) ?></th>
                            <?php endforeach; ?>
                            <th>EU</th><th>IT</th><th>UK</th><th>US</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($guide['rows'] as $row): ?>
                            <?php $id = (int) $row['id']; ?>
                            <tr>
                                <td>
                                    <label class="visually-hidden" for="row-<?= $id ?>-label">Talla</label>
                                    <input class="adm-input adm-input--sm" style="max-width:9rem;" type="text"
                                           id="row-<?= $id ?>-label" name="row[<?= $id ?>][label]"
                                           value="<?= e((string) $row['label']) ?>">
                                </td>

                                <?php foreach ($columns as $column): ?>
                                    <td>
                                        <label class="visually-hidden" for="row-<?= $id ?>-<?= e($column) ?>">
                                            <?= e(SizeGuide::header($column, $lang)) ?>
                                        </label>
                                        <input class="adm-input adm-input--sm" style="max-width:9rem;" type="text"
                                               id="row-<?= $id ?>-<?= e($column) ?>"
                                               name="row[<?= $id ?>][<?= e($column) ?>]"
                                               value="<?= e((string) $row[$column]) ?>">
                                    </td>
                                <?php endforeach; ?>

                                <?php foreach (['eu', 'it', 'uk', 'us'] as $market): ?>
                                    <td>
                                        <label class="visually-hidden" for="row-<?= $id ?>-<?= $market ?>">
                                            <?= strtoupper($market) ?>
                                        </label>
                                        <input class="adm-input adm-input--sm" style="max-width:7rem;" type="text"
                                               id="row-<?= $id ?>-<?= $market ?>"
                                               name="row[<?= $id ?>][<?= $market ?>]"
                                               value="<?= e((string) $row[$market]) ?>">
                                    </td>
                                <?php endforeach; ?>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </section>
    <?php endforeach; ?>

    <div class="actions-bar">
        <div class="spacer"></div>
        <button class="abtn" type="submit">Guardar tablas</button>
    </div>
</form>
