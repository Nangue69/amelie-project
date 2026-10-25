<?php

use App\Core\View;

/** @var array $entries */

View::share('pageTitle', 'Actividad');

$actions = [
    'login'   => 'entró en el panel',
    'logout'  => 'salió del panel',
    'create'  => 'creó',
    'update'  => 'modificó',
    'delete'  => 'eliminó',
    'archive' => 'archivó',
];

$entities = [
    'product'    => 'un producto',
    'variants'   => 'las tallas de un producto',
    'stock'      => 'las existencias',
    'categories' => 'las categorías',
    'order'      => 'un pedido',
    'page'       => 'una página',
    'post'       => 'una entrada del Journal',
    'faq'        => 'las preguntas frecuentes',
    'size_guide' => 'la guía de tallas',
    'texts'      => 'los textos de la web',
    'settings'   => 'los ajustes',
    'theme'      => 'el aspecto de la web',
    'shipping'   => 'los gastos de envío',
    'coupons'    => 'los códigos de descuento',
    'coupon'     => 'un código de descuento',
    'languages'  => 'los idiomas',
    'users'      => 'las cuentas de acceso',
    'user'       => 'una cuenta',
];
?>
<p class="page-intro">
    El registro de lo que se ha hecho en el panel. Útil para saber cuándo cambió algo y quién lo cambió.
</p>

<section class="card">
    <?php if ($entries === []): ?>
        <div class="empty"><p>Todavía no hay movimientos registrados.</p></div>
    <?php else: ?>
        <ul class="log">
            <?php foreach ($entries as $entry): ?>
                <?php
                $action = $actions[$entry['action']] ?? (string) $entry['action'];
                $entity = $entry['entity'] === '' ? '' : ($entities[$entry['entity']] ?? (string) $entry['entity']);
                ?>
                <li>
                    <strong><?= e((string) $entry['user_name']) ?></strong>
                    <span><?= e(trim($action . ' ' . $entity)) ?></span>
                    <?php if ($entry['description'] !== ''): ?>
                        <span style="color:var(--adm-soft);">(<?= e((string) $entry['description']) ?>)</span>
                    <?php endif; ?>
                    <time datetime="<?= e(format_date($entry['created_at'], 'Y-m-d\TH:i')) ?>">
                        <?= e(format_date($entry['created_at'], 'd/m/Y H:i')) ?>
                    </time>
                </li>
            <?php endforeach; ?>
        </ul>
    <?php endif; ?>
</section>
