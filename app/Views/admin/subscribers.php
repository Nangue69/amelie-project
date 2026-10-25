<?php

use App\Core\View;

/** @var array $subscribers */
/** @var string $status */
/** @var int $total */

View::share('pageTitle', 'Suscriptoras');

$labels = ['active' => 'Activa', 'pending' => 'Sin confirmar', 'unsubscribed' => 'Dada de baja'];
$tags   = ['active' => 'tag--ok', 'pending' => 'tag--warn', 'unsubscribed' => 'tag--muted'];
?>
<p class="page-intro">
    Quien ha dejado su correo para recibir novedades. Descarga el listado para usarlo en tu
    herramienta de envío de boletines.
</p>

<section class="card">
    <div class="card__head">
        <div>
            <h2><?= (int) $total ?> suscriptoras activas</h2>
            <p><?= count($subscribers) ?> registros en la lista que estás viendo.</p>
        </div>
        <div class="spacer"></div>

        <div class="filters">
            <a href="<?= e(admin_url('subscribers')) ?>" <?= $status === '' ? 'aria-current="page"' : '' ?>>Todas</a>
            <?php foreach ($labels as $value => $label): ?>
                <a href="<?= e(admin_url('subscribers?status=' . $value)) ?>"
                   <?= $status === $value ? 'aria-current="page"' : '' ?>><?= e($label) ?></a>
            <?php endforeach; ?>
        </div>

        <a class="abtn" href="<?= e(admin_url('subscribers/export')) ?>">Descargar CSV</a>
    </div>

    <?php if ($subscribers === []): ?>
        <div class="empty">
            <h3>Todavía no hay suscriptoras</h3>
            <p>El formulario del pie de página y el de la portada alimentan esta lista.</p>
        </div>
    <?php else: ?>
        <div class="table-wrap">
            <table class="table">
                <thead>
                    <tr><th>Correo</th><th>Idioma</th><th>Procedencia</th><th>Alta</th><th>Estado</th><th class="actions"></th></tr>
                </thead>
                <tbody>
                    <?php foreach ($subscribers as $subscriber): ?>
                        <tr>
                            <td><?= e((string) $subscriber['email']) ?></td>
                            <td><?= e(strtoupper((string) $subscriber['lang'])) ?></td>
                            <td style="color:var(--adm-faint);"><?= e((string) $subscriber['source']) ?></td>
                            <td><?= e(format_date($subscriber['created_at'])) ?></td>
                            <td>
                                <span class="tag <?= $tags[$subscriber['status']] ?? 'tag--muted' ?>">
                                    <?= e($labels[$subscriber['status']] ?? $subscriber['status']) ?>
                                </span>
                            </td>
                            <td class="actions">
                                <form action="<?= e(admin_url('subscribers/' . $subscriber['id'] . '/delete')) ?>" method="post"
                                      data-confirm="Se eliminará este correo de la lista. Es lo que corresponde si te lo piden por protección de datos."
                                      data-confirm-label="Sí, eliminar">
                                    <?= csrf_field() ?>
                                    <button class="abtn abtn--danger abtn--sm" type="submit">Eliminar</button>
                                </form>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    <?php endif; ?>
</section>
