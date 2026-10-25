<?php

use App\Core\View;

/** @var array $coupons */

View::share('pageTitle', 'Códigos de descuento');

$types = [
    'percent'       => 'Porcentaje',
    'fixed'         => 'Importe fijo',
    'free_shipping' => 'Envío gratuito',
];
?>
<p class="page-intro">
    Códigos que la clienta escribe en el carrito. Puedes limitarlos por fechas, por pedido
    mínimo o por número de usos.
</p>

<section class="card">
    <div class="card__head">
        <div><h2>Crear un código</h2></div>
    </div>

    <form action="<?= e(admin_url('coupons/new')) ?>" method="post">
        <?= csrf_field() ?>

        <div class="adm-grid">
            <div class="adm-field">
                <label for="code">Código</label>
                <input class="adm-input" type="text" id="code" name="code" required
                       placeholder="VERANO20" style="text-transform:uppercase;">
                <p class="hint">Sólo letras, números y guiones.</p>
            </div>

            <div class="adm-field">
                <label for="type">Tipo de descuento</label>
                <select class="adm-select" id="type" name="type">
                    <?php foreach ($types as $value => $label): ?>
                        <option value="<?= e($value) ?>"><?= e($label) ?></option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div class="adm-field">
                <label for="value">Valor</label>
                <input class="adm-input" type="text" id="value" name="value" inputmode="decimal" placeholder="20">
                <p class="hint">Un 20 significa 20 % o 20 €, según el tipo. En envío gratuito se ignora.</p>
            </div>

            <div class="adm-field">
                <label for="min_amount">Pedido mínimo</label>
                <input class="adm-input" type="text" id="min_amount" name="min_amount" inputmode="decimal" value="0">
            </div>
        </div>

        <button class="abtn abtn--ghost" type="submit">Crear código</button>
    </form>
</section>

<form action="<?= e(admin_url('coupons')) ?>" method="post" data-dirty-guard style="margin-top:2.4rem;">
    <?= csrf_field() ?>

    <section class="card">
        <div class="card__head">
            <div><h2><?= count($coupons) ?> códigos</h2></div>
        </div>

        <?php if ($coupons === []): ?>
            <div class="empty"><p>Todavía no has creado ningún código.</p></div>
        <?php else: ?>
            <div class="table-wrap">
                <table class="table">
                    <thead>
                        <tr>
                            <th>Código</th><th>Tipo</th><th class="num">Valor</th><th class="num">Mínimo</th>
                            <th>Desde</th><th>Hasta</th><th class="num">Usos</th><th>Activo</th><th class="actions"></th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($coupons as $coupon): ?>
                            <?php $id = (int) $coupon['id']; ?>
                            <tr>
                                <td><strong><?= e((string) $coupon['code']) ?></strong></td>

                                <td>
                                    <label class="visually-hidden" for="c-type-<?= $id ?>">Tipo</label>
                                    <select class="adm-select adm-input--sm" id="c-type-<?= $id ?>"
                                            name="coupon[<?= $id ?>][type]" style="min-width:15rem;">
                                        <?php foreach ($types as $value => $label): ?>
                                            <option value="<?= e($value) ?>" <?= $coupon['type'] === $value ? 'selected' : '' ?>>
                                                <?= e($label) ?>
                                            </option>
                                        <?php endforeach; ?>
                                    </select>
                                </td>

                                <td class="num">
                                    <label class="visually-hidden" for="c-value-<?= $id ?>">Valor</label>
                                    <input class="adm-input adm-input--sm adm-input--num" type="text" inputmode="decimal"
                                           id="c-value-<?= $id ?>" name="coupon[<?= $id ?>][value]"
                                           value="<?= e(number_format((float) $coupon['value'], 2, ',', '')) ?>">
                                </td>

                                <td class="num">
                                    <label class="visually-hidden" for="c-min-<?= $id ?>">Pedido mínimo</label>
                                    <input class="adm-input adm-input--sm adm-input--num" type="text" inputmode="decimal"
                                           id="c-min-<?= $id ?>" name="coupon[<?= $id ?>][min_amount]"
                                           value="<?= e(number_format((float) $coupon['min_amount'], 2, ',', '')) ?>">
                                </td>

                                <td>
                                    <label class="visually-hidden" for="c-from-<?= $id ?>">Desde</label>
                                    <input class="adm-input adm-input--sm" type="date" id="c-from-<?= $id ?>"
                                           name="coupon[<?= $id ?>][starts_at]" value="<?= e((string) $coupon['starts_at']) ?>">
                                </td>

                                <td>
                                    <label class="visually-hidden" for="c-to-<?= $id ?>">Hasta</label>
                                    <input class="adm-input adm-input--sm" type="date" id="c-to-<?= $id ?>"
                                           name="coupon[<?= $id ?>][ends_at]" value="<?= e((string) $coupon['ends_at']) ?>">
                                </td>

                                <td class="num">
                                    <?= (int) $coupon['used_count'] ?>
                                    <label class="visually-hidden" for="c-limit-<?= $id ?>">Límite de usos</label>
                                    <input class="adm-input adm-input--sm adm-input--num" type="number" min="1"
                                           id="c-limit-<?= $id ?>" name="coupon[<?= $id ?>][usage_limit]"
                                           value="<?= $coupon['usage_limit'] === null ? '' : (int) $coupon['usage_limit'] ?>"
                                           placeholder="sin límite">
                                </td>

                                <td>
                                    <label class="check">
                                        <input type="checkbox" name="coupon[<?= $id ?>][is_active]" value="1"
                                               <?= (int) $coupon['is_active'] === 1 ? 'checked' : '' ?>>
                                        <span class="visually-hidden">Activo</span>
                                    </label>
                                </td>

                                <td class="actions"></td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>

            <div class="actions-bar">
                <div class="spacer"></div>
                <button class="abtn" type="submit">Guardar códigos</button>
            </div>
        <?php endif; ?>
    </section>
</form>

<?php if ($coupons !== []): ?>
    <section class="card" style="margin-top:2.4rem;border-color:#E3C9C9;">
        <div class="card__head" style="border-bottom-color:#E3C9C9;">
            <div><h2 style="color:var(--adm-danger);">Eliminar un código</h2></div>
        </div>

        <div style="display:flex;gap:.8rem;flex-wrap:wrap;">
            <?php foreach ($coupons as $coupon): ?>
                <form action="<?= e(admin_url('coupons/' . $coupon['id'] . '/delete')) ?>" method="post"
                      data-confirm="Se eliminará el código <?= e((string) $coupon['code']) ?>. Quien lo tenga ya no podrá usarlo."
                      data-confirm-label="Sí, eliminar">
                    <?= csrf_field() ?>
                    <button class="abtn abtn--danger abtn--sm" type="submit"><?= e((string) $coupon['code']) ?></button>
                </form>
            <?php endforeach; ?>
        </div>
    </section>
<?php endif; ?>
