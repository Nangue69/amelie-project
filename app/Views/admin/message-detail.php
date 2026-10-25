<?php

use App\Core\View;

/** @var array $message */

View::share('pageTitle', 'Mensaje de ' . $message['name']);

$subject = $message['subject'] !== '' ? (string) $message['subject'] : 'tu mensaje';
$mailto  = 'mailto:' . rawurlencode((string) $message['email'])
    . '?subject=' . rawurlencode('Re: ' . $subject);
?>
<p class="page-intro">
    <a href="<?= e(admin_url('messages')) ?>" style="color:var(--adm-brand);">&larr; Volver a mensajes</a>
</p>

<section class="card" style="max-width:82rem;">
    <div class="card__head">
        <div>
            <h2><?= e($message['subject'] !== '' ? (string) $message['subject'] : 'Sin asunto') ?></h2>
            <p>
                <?= e((string) $message['name']) ?> &middot;
                <a href="mailto:<?= e((string) $message['email']) ?>" style="color:var(--adm-brand);">
                    <?= e((string) $message['email']) ?>
                </a>
                &middot; <?= e(format_date($message['created_at'], 'd/m/Y H:i')) ?>
                &middot; <?= e(strtoupper((string) $message['lang'])) ?>
            </p>
        </div>
    </div>

    <div style="padding:2.4rem;background:var(--adm-bg);border-radius:.6rem;line-height:1.8;">
        <?= nl2br(e((string) $message['body'])) ?>
    </div>

    <div class="actions-bar">
        <a class="abtn" href="<?= e($mailto) ?>">Responder por correo</a>
        <div class="spacer"></div>

        <form action="<?= e(admin_url('messages/' . $message['id'] . '/delete')) ?>" method="post"
              data-confirm="Se eliminará este mensaje. Esta acción no se puede deshacer."
              data-confirm-label="Sí, eliminar">
            <?= csrf_field() ?>
            <button class="abtn abtn--danger" type="submit">Eliminar mensaje</button>
        </form>
    </div>
</section>
