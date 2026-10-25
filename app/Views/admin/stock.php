<?php

use App\Core\View;

/** @var array $grouped */
/** @var int $threshold */

View::share('pageTitle', 'Existencias');
?>
<p class="page-intro">
    Cuántas unidades te quedan de cada talla. Cambia los números y pulsa guardar: la tienda
    deja de vender una talla en cuanto llega a cero.
</p>

<form action="<?= e(admin_url('stock')) ?>" method="post" data-dirty-guard>
    <?= csrf_field() ?>

    <?php if ($grouped === []): ?>
        <section class="card">
            <div class="empty">
                <h3>Todavía no hay tallas</h3>
                <p>Crea un producto y añádele tallas para verlas aquí.</p>
            </div>
        </section>
    <?php else: ?>
        <?php foreach ($grouped as $productName => $rows): ?>
            <section class="card" style="margin-bottom:2.4rem;">
                <div class="card__head">
                    <div><h2><?= e((string) $productName) ?></h2></div>
                </div>

                <div class="table-wrap">
                    <table class="table">
                        <thead>
                            <tr><th>Talla</th><th>Referencia</th><th class="num">Unidades</th><th>Situación</th></tr>
                        </thead>
                        <tbody>
                            <?php foreach ($rows as $row): ?>
                                <?php $stock = (int) $row['stock']; ?>
                                <tr>
                                    <td>
                                        <strong>
                                            <?= $row['size_mode'] === 'bra_brief'
                                                ? e($row['size_bra'] . ' / ' . $row['size_brief'])
                                                : e((string) $row['size']) ?>
                                        </strong>
                                        <?php if ((int) $row['is_active'] !== 1): ?>
                                            <span class="tag tag--muted">Desactivada</span>
                                        <?php endif; ?>
                                    </td>
                                    <td style="color:var(--adm-faint);"><?= e((string) $row['sku']) ?></td>
                                    <td class="num">
                                        <label class="visually-hidden" for="stock-<?= (int) $row['id'] ?>">Unidades</label>
                                        <input class="adm-input adm-input--sm adm-input--num" type="number" min="0"
                                               id="stock-<?= (int) $row['id'] ?>"
                                               name="stock[<?= (int) $row['id'] ?>]" value="<?= $stock ?>">
                                    </td>
                                    <td>
                                        <?php if ($stock === 0): ?>
                                            <span class="tag tag--danger">Agotada</span>
                                        <?php elseif ($stock <= $threshold): ?>
                                            <span class="tag tag--warn">Quedan pocas</span>
                                        <?php else: ?>
                                            <span class="tag tag--ok">Disponible</span>
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
                Se avisa de existencias bajas por debajo de <?= (int) $threshold ?> unidades.
            </span>
            <div class="spacer"></div>
            <button class="abtn" type="submit">Guardar existencias</button>
        </div>
    <?php endif; ?>
</form>
