<?php

use App\Core\View;

View::share('pageTitle', 'Nueva entrada');
?>
<p class="page-intro">
    Empieza por el título. Después escribirás el texto en cada idioma y elegirás la fotografía de portada.
</p>

<form class="card" action="<?= e(admin_url('posts/new')) ?>" method="post" style="max-width:68rem;">
    <?= csrf_field() ?>

    <div class="adm-field">
        <label for="title">Título de la entrada</label>
        <input class="adm-input" type="text" id="title" name="title" required autofocus
               placeholder="Por ejemplo: El arte de la corsetería italiana">
    </div>

    <div class="actions-bar">
        <a class="abtn abtn--ghost" href="<?= e(admin_url('posts')) ?>">Cancelar</a>
        <div class="spacer"></div>
        <button class="abtn" type="submit">Crear y continuar</button>
    </div>
</form>
