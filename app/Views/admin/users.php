<?php

use App\Core\Auth;
use App\Core\View;

/** @var array $users */

View::share('pageTitle', 'Cuentas de acceso');
?>
<p class="page-intro">
    Quién puede entrar en el panel. Una cuenta de <strong>administración</strong> lo controla
    todo; una de <strong>edición</strong> puede gestionar productos, pedidos y contenido,
    pero no tocar la configuración ni las cuentas.
</p>

<section class="card">
    <div class="card__head">
        <div><h2>Crear una cuenta</h2></div>
    </div>

    <form action="<?= e(admin_url('users/new')) ?>" method="post">
        <?= csrf_field() ?>

        <div class="adm-grid">
            <div class="adm-field">
                <label for="name">Nombre</label>
                <input class="adm-input" type="text" id="name" name="name" required>
            </div>

            <div class="adm-field">
                <label for="email">Correo electrónico</label>
                <input class="adm-input" type="email" id="email" name="email" required autocomplete="off">
            </div>

            <div class="adm-field">
                <label for="password">Contraseña</label>
                <input class="adm-input" type="password" id="password" name="password" required
                       minlength="8" autocomplete="new-password">
                <p class="hint">Mínimo 8 caracteres.</p>
            </div>

            <div class="adm-field">
                <label for="role">Permisos</label>
                <select class="adm-select" id="role" name="role">
                    <option value="editor">Edición</option>
                    <option value="admin">Administración</option>
                </select>
            </div>
        </div>

        <button class="abtn abtn--ghost" type="submit">Crear cuenta</button>
    </form>
</section>

<form action="<?= e(admin_url('users')) ?>" method="post" style="margin-top:2.4rem;">
    <?= csrf_field() ?>

    <section class="card">
        <div class="card__head">
            <div><h2><?= count($users) ?> cuentas</h2><p>Deja la contraseña en blanco si no quieres cambiarla.</p></div>
        </div>

        <div class="table-wrap">
            <table class="table">
                <thead>
                    <tr>
                        <th>Nombre</th><th>Correo</th><th>Permisos</th>
                        <th>Nueva contraseña</th><th>Activa</th><th>Último acceso</th><th class="actions"></th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($users as $account): ?>
                        <?php
                        $id    = (int) $account['id'];
                        $isMe  = $id === Auth::id();
                        ?>
                        <tr>
                            <td>
                                <label class="visually-hidden" for="u-name-<?= $id ?>">Nombre</label>
                                <input class="adm-input adm-input--sm" type="text" id="u-name-<?= $id ?>"
                                       name="user[<?= $id ?>][name]" value="<?= e((string) $account['name']) ?>">
                                <?php if ($isMe): ?>
                                    <span class="tag tag--info">Tú</span>
                                <?php endif; ?>
                            </td>

                            <td style="color:var(--adm-faint);"><?= e((string) $account['email']) ?></td>

                            <td>
                                <label class="visually-hidden" for="u-role-<?= $id ?>">Permisos</label>
                                <select class="adm-select adm-input--sm" id="u-role-<?= $id ?>"
                                        name="user[<?= $id ?>][role]" style="min-width:15rem;">
                                    <option value="editor" <?= $account['role'] === 'editor' ? 'selected' : '' ?>>Edición</option>
                                    <option value="admin" <?= $account['role'] === 'admin' ? 'selected' : '' ?>>Administración</option>
                                </select>
                            </td>

                            <td>
                                <label class="visually-hidden" for="u-pass-<?= $id ?>">Nueva contraseña</label>
                                <input class="adm-input adm-input--sm" type="password" id="u-pass-<?= $id ?>"
                                       name="user[<?= $id ?>][password]" autocomplete="new-password"
                                       placeholder="Sin cambios">
                            </td>

                            <td>
                                <label class="check">
                                    <input type="checkbox" name="user[<?= $id ?>][is_active]" value="1"
                                           <?= (int) $account['is_active'] === 1 ? 'checked' : '' ?>
                                           <?= $isMe ? 'disabled checked' : '' ?>>
                                    <span class="visually-hidden">Activa</span>
                                </label>
                            </td>

                            <td style="color:var(--adm-faint);">
                                <?= $account['last_login_at'] === null ? 'Nunca' : e(format_date($account['last_login_at'], 'd/m/Y H:i')) ?>
                            </td>

                            <td class="actions">
                                <?php if (!$isMe): ?>
                                    <button class="abtn abtn--danger abtn--sm" type="submit"
                                            form="delete-user-<?= $id ?>">Eliminar</button>
                                <?php endif; ?>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>

        <div class="actions-bar">
            <div class="spacer"></div>
            <button class="abtn" type="submit">Guardar cuentas</button>
        </div>
    </section>
</form>

<?php foreach ($users as $account): ?>
    <?php if ((int) $account['id'] === Auth::id()) { continue; } ?>
    <form id="delete-user-<?= (int) $account['id'] ?>" method="post"
          action="<?= e(admin_url('users/' . $account['id'] . '/delete')) ?>"
          data-confirm="Se eliminará la cuenta de <?= e((string) $account['name']) ?>. Dejará de poder entrar en el panel."
          data-confirm-label="Sí, eliminar" hidden>
        <?= csrf_field() ?>
    </form>
<?php endforeach; ?>
