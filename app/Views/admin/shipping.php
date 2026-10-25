<?php

use App\Core\View;

/** @var array $rates */

View::share('pageTitle', 'Gastos de envío');
?>
<p class="page-intro">
    Cuánto cobras por enviar a cada zona. Se aplica la primera zona que incluya el país de
    la clienta; la zona con un asterisco <code>*</code> recoge todo lo demás.
</p>

<form action="<?= e(admin_url('shipping')) ?>" method="post" data-dirty-guard>
    <?= csrf_field() ?>

    <?php foreach ($rates as $rate): ?>
        <?php $id = (int) $rate['id']; ?>
        <section class="card" style="margin-bottom:2.4rem;">
            <div class="card__head">
                <div><h2 style="font-size:1.7rem;"><?= e((string) $rate['zone_name']) ?></h2></div>
                <div class="spacer"></div>
                <label class="switch">
                    <input type="checkbox" name="rate[<?= $id ?>][is_active]" value="1"
                           <?= (int) $rate['is_active'] === 1 ? 'checked' : '' ?>>
                    <span class="switch__track"></span>
                    <span>Activa</span>
                </label>
            </div>

            <div class="adm-grid">
                <div class="adm-field">
                    <label for="rate-name-<?= $id ?>">Nombre de la zona</label>
                    <input class="adm-input" type="text" id="rate-name-<?= $id ?>"
                           name="rate[<?= $id ?>][zone_name]" value="<?= e((string) $rate['zone_name']) ?>">
                </div>

                <div class="adm-field">
                    <label for="rate-countries-<?= $id ?>">Países</label>
                    <input class="adm-input" type="text" id="rate-countries-<?= $id ?>"
                           name="rate[<?= $id ?>][countries]" value="<?= e((string) $rate['countries']) ?>">
                    <p class="hint">Códigos de dos letras separados por comas: ES,NL,IT. Un asterisco para el resto del mundo.</p>
                </div>

                <div class="adm-field">
                    <label for="rate-price-<?= $id ?>">Precio del envío</label>
                    <input class="adm-input" type="text" inputmode="decimal" id="rate-price-<?= $id ?>"
                           name="rate[<?= $id ?>][price]"
                           value="<?= e(number_format((float) $rate['price'], 2, ',', '')) ?>">
                </div>

                <div class="adm-field">
                    <label for="rate-free-<?= $id ?>">Gratis a partir de</label>
                    <input class="adm-input" type="text" inputmode="decimal" id="rate-free-<?= $id ?>"
                           name="rate[<?= $id ?>][free_from]"
                           value="<?= $rate['free_from'] === null ? '' : e(number_format((float) $rate['free_from'], 2, ',', '')) ?>">
                    <p class="hint">Déjalo en blanco si nunca es gratis en esta zona.</p>
                </div>

                <div class="adm-field">
                    <label for="rate-days-<?= $id ?>">Plazo de entrega</label>
                    <input class="adm-input" type="text" id="rate-days-<?= $id ?>"
                           name="rate[<?= $id ?>][delivery_days]" value="<?= e((string) $rate['delivery_days']) ?>">
                    <p class="hint">Por ejemplo: 2-3 días laborables.</p>
                </div>

                <div class="adm-field">
                    <label for="rate-pos-<?= $id ?>">Orden</label>
                    <input class="adm-input" type="number" id="rate-pos-<?= $id ?>"
                           name="rate[<?= $id ?>][position]" value="<?= (int) $rate['position'] ?>">
                </div>
            </div>
        </section>
    <?php endforeach; ?>

    <div class="actions-bar">
        <div class="spacer"></div>
        <button class="abtn" type="submit">Guardar zonas de envío</button>
    </div>
</form>
