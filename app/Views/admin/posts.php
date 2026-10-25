<?php

use App\Core\Lang;
use App\Core\View;

/** @var array $posts */

View::share('pageTitle', 'Journal');
?>
<p class="page-intro">
    El blog de la marca. Sirve para contar el oficio detrás de cada pieza y, de paso,
    para que Google encuentre la tienda.
</p>

<section class="card">
    <div class="card__head">
        <div><h2><?= count($posts) ?> entradas</h2></div>
        <div class="spacer"></div>
        <a class="abtn" href="<?= e(admin_url('posts/new')) ?>">Nueva entrada</a>
    </div>

    <?php if ($posts === []): ?>
        <div class="empty">
            <h3>Todavía no hay entradas</h3>
            <p>Escribe la primera cuando quieras.</p>
        </div>
    <?php else: ?>
        <div class="table-wrap">
            <table class="table">
                <thead>
                    <tr><th></th><th>Título</th><th>Fecha</th><th>Estado</th><th class="actions"></th></tr>
                </thead>
                <tbody>
                    <?php foreach ($posts as $post): ?>
                        <tr>
                            <td><img class="table__media" src="<?= e(img((string) $post['cover_image'])) ?>" alt="" loading="lazy"></td>
                            <td>
                                <a href="<?= e(admin_url('posts/' . $post['id'])) ?>" style="color:var(--adm-brand);">
                                    <strong><?= e((string) ($post['title'] ?? $post['slug'])) ?></strong>
                                </a><br>
                                <small style="color:var(--adm-faint);">/<?= e((string) $post['slug']) ?></small>
                            </td>
                            <td><?= e(format_date($post['published_at'])) ?></td>
                            <td>
                                <span class="tag <?= $post['status'] === 'published' ? 'tag--ok' : 'tag--warn' ?>">
                                    <?= $post['status'] === 'published' ? 'Publicada' : 'Borrador' ?>
                                </span>
                            </td>
                            <td class="actions">
                                <?php if ($post['status'] === 'published'): ?>
                                    <a class="abtn abtn--ghost abtn--sm" target="_blank" rel="noopener"
                                       href="<?= e(lang_url('journal/' . $post['slug'], Lang::default())) ?>">Ver</a>
                                <?php endif; ?>
                                <a class="abtn abtn--ghost abtn--sm" href="<?= e(admin_url('posts/' . $post['id'])) ?>">Editar</a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    <?php endif; ?>
</section>
