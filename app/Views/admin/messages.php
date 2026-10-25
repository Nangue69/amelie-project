<?php

use App\Core\View;

/** @var array $messages */

View::share('pageTitle', 'Mensajes');
?>
<p class="page-intro">
    Lo que llega desde el formulario de contacto. Los sin leer aparecen en negrita.
</p>

<section class="card">
    <?php if ($messages === []): ?>
        <div class="empty">
            <h3>No hay mensajes</h3>
            <p>Aquí aparecerá lo que escriban desde la página de contacto.</p>
        </div>
    <?php else: ?>
        <div class="table-wrap">
            <table class="table">
                <thead>
                    <tr><th>De</th><th>Asunto</th><th>Recibido</th><th class="actions"></th></tr>
                </thead>
                <tbody>
                    <?php foreach ($messages as $message): ?>
                        <?php $unread = (int) $message['is_read'] === 0; ?>
                        <tr>
                            <td>
                                <strong style="<?= $unread ? '' : 'font-weight:300;' ?>">
                                    <?= e((string) $message['name']) ?>
                                </strong><br>
                                <small style="color:var(--adm-faint);"><?= e((string) $message['email']) ?></small>
                            </td>
                            <td>
                                <?= e($message['subject'] !== '' ? (string) $message['subject'] : excerpt((string) $message['body'], 60)) ?>
                                <?php if ($unread): ?>
                                    <span class="tag tag--warn">Sin leer</span>
                                <?php endif; ?>
                            </td>
                            <td><?= e(format_date($message['created_at'], 'd/m/Y H:i')) ?></td>
                            <td class="actions">
                                <a class="abtn abtn--ghost abtn--sm" href="<?= e(admin_url('messages/' . $message['id'])) ?>">Leer</a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    <?php endif; ?>
</section>
