<?php
/** @var string $email */
?>
<main class="login-card">
    <img src="<?= e(asset('img/brand/Wordmark_Oro_Trasparente.svg')) ?>" alt="<?= e($siteName) ?>" width="180" height="29">
    <h1>Panel de gestión</h1>

    <form action="<?= e(admin_url('login')) ?>" method="post" novalidate>
        <?= csrf_field() ?>

        <div class="adm-field">
            <label for="email">Correo electrónico</label>
            <input class="adm-input" type="email" id="email" name="email" required
                   autocomplete="username" autofocus value="<?= e($email) ?>">
        </div>

        <div class="adm-field">
            <label for="password">Contraseña</label>
            <input class="adm-input" type="password" id="password" name="password" required
                   autocomplete="current-password">
        </div>

        <button class="abtn abtn--block" type="submit" style="margin-top:1.6rem;">Entrar</button>
    </form>

    <p class="login-foot">
        ¿Has olvidado la contraseña? Escribe a quien administra la tienda para que te la restablezca.
    </p>
</main>
