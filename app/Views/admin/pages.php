<?php

use App\Core\Lang;
use App\Core\View;

/** @var array $pages */

View::share('pageTitle', 'Páginas');

$descriptions = [
    'our-story'        => 'La historia y la filosofía de la marca.',
    'size-guide'       => 'Cómo medirse y elegir talla. Las tablas se editan en «Guía de tallas».',
    'shipping-returns' => 'Plazos, costes de envío y condiciones de devolución.',
    'faq'              => 'Texto de cabecera. Las preguntas se editan en «Preguntas frecuentes».',
    'contact'          => 'Texto de cabecera del formulario de contacto.',
    'privacy'          => 'Política de privacidad.',
    'cookies'          => 'Política de cookies.',
    'terms'            => 'Términos y condiciones de compra.',
];
?>
<p class="page-intro">
    Los textos fijos de la web. Cada uno se escribe en los cuatro idiomas.
</p>

<section class="card">
    <div class="table-wrap">
        <table class="table">
            <thead>
                <tr><th>Página</th><th>Dirección</th><th>Visible</th><th class="actions"></th></tr>
            </thead>
            <tbody>
                <?php foreach ($pages as $page): ?>
                    <tr>
                        <td>
                            <strong><?= e((string) ($page['title'] ?? $page['slug'])) ?></strong><br>
                            <small style="color:var(--adm-faint);"><?= e($descriptions[$page['slug']] ?? '') ?></small>
                        </td>
                        <td style="color:var(--adm-faint);">/<?= e(Lang::default()) ?>/<?= e((string) $page['slug']) ?></td>
                        <td>
                            <span class="tag <?= (int) $page['is_active'] === 1 ? 'tag--ok' : 'tag--muted' ?>">
                                <?= (int) $page['is_active'] === 1 ? 'Publicada' : 'Oculta' ?>
                            </span>
                        </td>
                        <td class="actions">
                            <a class="abtn abtn--ghost abtn--sm" target="_blank" rel="noopener"
                               href="<?= e(lang_url((string) $page['slug'], Lang::default())) ?>">Ver</a>
                            <a class="abtn abtn--ghost abtn--sm" href="<?= e(admin_url('pages/' . $page['id'])) ?>">Editar</a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</section>
