<?php

use App\Core\Lang;
use App\Core\View;
use App\Models\Theme;

/** @var array $themes */
/** @var string $active */

View::share('pageTitle', 'Aspecto de la web');
?>
<p class="page-intro">
    Cuatro combinaciones de color para la tienda. La estructura y las fotografías no cambian:
    sólo los colores. Puedes probarlas y volver atrás cuando quieras.
</p>

<form action="<?= e(admin_url('themes')) ?>" method="post">
    <?= csrf_field() ?>

    <div class="theme-grid">
        <?php foreach ($themes as $theme): ?>
            <?php
            $key      = (string) $theme['tkey'];
            $isActive = $key === $active;
            $colors   = Theme::swatchColors($theme);
            ?>
            <article class="theme-card <?= $isActive ? 'is-active' : '' ?>">
                <div class="theme-card__swatch" aria-hidden="true">
                    <?php foreach ($colors as $color): ?>
                        <span style="background: <?= e($color) ?>;"></span>
                    <?php endforeach; ?>
                </div>

                <div class="theme-card__body">
                    <h3>
                        <?= e((string) $theme['name']) ?>
                        <?php if ($isActive): ?>
                            <span class="tag tag--ok">En uso</span>
                        <?php endif; ?>
                    </h3>
                    <p><?= e((string) $theme['description']) ?></p>

                    <?php if (!$isActive): ?>
                        <button class="abtn abtn--block" type="submit" name="theme" value="<?= e($key) ?>">
                            Aplicar este aspecto
                        </button>
                    <?php else: ?>
                        <a class="abtn abtn--ghost abtn--block" target="_blank" rel="noopener"
                           href="<?= e(lang_url('', Lang::default())) ?>">Ver la web</a>
                    <?php endif; ?>
                </div>
            </article>
        <?php endforeach; ?>
    </div>
</form>
