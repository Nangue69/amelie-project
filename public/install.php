<?php

/**
 * AMÉLIE — Instalador
 *
 * Crea la base de datos, carga el contenido inicial y escribe config/config.php.
 * En cuanto termina, BORRA ESTE ARCHIVO del servidor.
 */

declare(strict_types=1);

const ROOT       = __DIR__ . '/..';
const CONFIG     = ROOT . '/config/config.php';
const LOCK       = ROOT . '/config/installed.lock';
const SQL_FILES  = [
    'database/schema.sql',
    'database/seed_01_core.sql',
    'database/seed_02_ui_es_en.sql',
    'database/seed_03_ui_nl_it.sql',
    'database/seed_04_catalog.sql',
    'database/seed_05_pages.sql',
    'database/seed_06_legal.sql',
    'database/seed_07_faq_blog.sql',
];

session_start();

function esc(?string $value): string
{
    return htmlspecialchars((string) $value, ENT_QUOTES, 'UTF-8');
}

function requirements(): array
{
    return [
        'PHP 8.0 o superior'      => PHP_VERSION_ID >= 80000,
        'Extensión PDO MySQL'     => extension_loaded('pdo_mysql'),
        'Extensión mbstring'      => extension_loaded('mbstring'),
        'Extensión fileinfo'      => extension_loaded('fileinfo'),
        'Carpeta config/ escribible'  => is_writable(ROOT . '/config'),
        'Carpeta storage/ escribible' => is_writable(ROOT . '/storage'),
        'Carpeta public/uploads/ escribible' => is_writable(__DIR__ . '/uploads'),
    ];
}

$alreadyInstalled = is_file(LOCK);
$errors = [];
$done   = false;

if ($_SERVER['REQUEST_METHOD'] === 'POST' && !$alreadyInstalled) {
    $token = $_POST['token'] ?? '';

    if (!is_string($token) || !hash_equals((string) ($_SESSION['install_token'] ?? ''), $token)) {
        $errors[] = 'La página había caducado. Vuelve a enviar el formulario.';
    } else {
        $host     = trim((string) ($_POST['db_host'] ?? 'localhost'));
        $port     = (int) ($_POST['db_port'] ?? 3306);
        $name     = trim((string) ($_POST['db_name'] ?? ''));
        $user     = trim((string) ($_POST['db_user'] ?? ''));
        $pass     = (string) ($_POST['db_pass'] ?? '');

        $adminName  = trim((string) ($_POST['admin_name'] ?? ''));
        $adminEmail = trim((string) ($_POST['admin_email'] ?? ''));
        $adminPass  = (string) ($_POST['admin_pass'] ?? '');

        if ($name === '') {
            $errors[] = 'Escribe el nombre de la base de datos.';
        }

        if ($adminName === '') {
            $errors[] = 'Escribe tu nombre.';
        }

        if (filter_var($adminEmail, FILTER_VALIDATE_EMAIL) === false) {
            $errors[] = 'El correo de acceso no es válido.';
        }

        if (mb_strlen($adminPass) < 8) {
            $errors[] = 'La contraseña debe tener al menos 8 caracteres.';
        }

        if ($errors === []) {
            try {
                $pdo = new PDO(
                    "mysql:host={$host};port={$port};charset=utf8mb4",
                    $user,
                    $pass,
                    [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]
                );

                $pdo->exec("CREATE DATABASE IF NOT EXISTS `{$name}` CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci");
                $pdo->exec("USE `{$name}`");

                foreach (SQL_FILES as $file) {
                    $path = ROOT . '/' . $file;

                    if (!is_file($path)) {
                        throw new RuntimeException("Falta el archivo {$file}. Súbelo y vuelve a intentarlo.");
                    }

                    $pdo->exec((string) file_get_contents($path));
                }

                // Sustituir la cuenta de ejemplo por la de quien instala.
                $pdo->exec('DELETE FROM `users`');

                $statement = $pdo->prepare(
                    'INSERT INTO `users` (`name`, `email`, `password`, `role`, `is_active`)
                     VALUES (:name, :email, :password, "admin", 1)'
                );

                $statement->execute([
                    'name'     => $adminName,
                    'email'    => mb_strtolower($adminEmail),
                    'password' => password_hash($adminPass, PASSWORD_BCRYPT),
                ]);

                $config = "<?php\n\ndeclare(strict_types=1);\n\nreturn [\n"
                    . "    'db' => [\n"
                    . "        'host'    => " . var_export($host, true) . ",\n"
                    . "        'port'    => " . $port . ",\n"
                    . "        'name'    => " . var_export($name, true) . ",\n"
                    . "        'user'    => " . var_export($user, true) . ",\n"
                    . "        'pass'    => " . var_export($pass, true) . ",\n"
                    . "        'charset' => 'utf8mb4',\n"
                    . "    ],\n"
                    . "    'app' => [\n"
                    . "        'debug'    => false,\n"
                    . "        'timezone' => 'Europe/Madrid',\n"
                    . "        'key'      => " . var_export(bin2hex(random_bytes(16)), true) . ",\n"
                    . "    ],\n"
                    . "    'mail' => [\n"
                    . "        'from_name'  => 'AMÉLIE',\n"
                    . "        'from_email' => " . var_export(mb_strtolower($adminEmail), true) . ",\n"
                    . "    ],\n"
                    . "];\n";

                if (file_put_contents(CONFIG, $config) === false) {
                    throw new RuntimeException('No se ha podido escribir config/config.php. Revisa los permisos de la carpeta config.');
                }

                file_put_contents(LOCK, date('c'));
                @unlink(ROOT . '/storage/cache/settings.php');

                $done = true;
            } catch (Throwable $e) {
                $errors[] = $e->getMessage();
            }
        }
    }
}

$_SESSION['install_token'] = bin2hex(random_bytes(16));
$token = $_SESSION['install_token'];
$checks = requirements();
$ready  = !in_array(false, $checks, true);
?>
<!doctype html>
<html lang="es">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<meta name="robots" content="noindex, nofollow">
<title>Instalación · AMÉLIE</title>
<style>
    *,*::before,*::after { box-sizing: border-box; }
    html { font-size: 62.5%; }
    body {
        margin: 0; min-height: 100vh; padding: 4rem 2rem;
        background: linear-gradient(135deg, #4A1527, #2A2024);
        color: #2A2024; font: 300 1.5rem/1.7 'Jost', 'Helvetica Neue', Arial, sans-serif;
    }
    .box {
        width: min(72rem, 100%); margin: 0 auto; padding: 4rem;
        background: #fff; border-top: 3px solid #C4A36A; border-radius: .6rem;
        box-shadow: 0 2rem 6rem rgba(0,0,0,.3);
    }
    h1 { margin: 0 0 .6rem; font-size: 1.2rem; letter-spacing: .3em; text-transform: uppercase; color: #C4A36A; }
    h2 { margin: 0 0 2.4rem; font-size: 3rem; font-weight: 300; color: #651D36;
         font-family: 'Cormorant Garamond', Georgia, serif; }
    h3 { margin: 3.2rem 0 1.2rem; font-size: 1.7rem; color: #651D36; }
    p  { margin: 0 0 1.6rem; color: #6E6166; }
    label { display: block; margin-bottom: .5rem; font-size: 1.3rem; color: #2A2024; }
    input {
        width: 100%; min-height: 4.6rem; padding: 1rem 1.4rem; margin-bottom: 1.6rem;
        border: 1px solid #E7DFD3; border-radius: .2rem; font: inherit; color: #2A2024;
    }
    input:focus { outline: none; border-color: #C4A36A; box-shadow: 0 0 0 3px rgba(196,163,106,.2); }
    .grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(24rem, 1fr)); gap: 0 1.6rem; }
    .hint { margin: -1.2rem 0 1.6rem; font-size: 1.25rem; color: #9A8F93; }
    button {
        min-height: 5rem; padding: 1.2rem 3.2rem; border: 0; border-radius: .2rem;
        background: #651D36; color: #fff; font: inherit; font-size: 1.4rem;
        letter-spacing: .1em; cursor: pointer;
    }
    button:hover { background: #7C2442; }
    button[disabled] { opacity: .4; cursor: not-allowed; }
    ul.checks { margin: 0 0 2.4rem; padding: 0; list-style: none; }
    ul.checks li { padding: .9rem 0; border-bottom: 1px solid #F1EBE1; font-size: 1.4rem; }
    .ok   { color: #3F6B52; }
    .bad  { color: #9B3B3B; }
    .alert {
        padding: 1.4rem 1.8rem; margin-bottom: 2.4rem;
        border-left: 3px solid #9B3B3B; background: #FBF4F4; font-size: 1.4rem;
    }
    .alert--ok { border-left-color: #3F6B52; background: #F2F7F4; }
    .alert--warn { border-left-color: #9A6B24; background: #FBF5EA; }
    code { background: #F7F4EE; padding: .2rem .6rem; border-radius: .2rem; font-size: 1.3rem; }
    a { color: #651D36; }
</style>
</head>
<body>
<div class="box">
    <h1>AMÉLIE</h1>

    <?php if ($done): ?>
        <h2>Listo</h2>

        <div class="alert alert--ok">
            La tienda está instalada y ya tiene la Colección 01 cargada en los cuatro idiomas.
        </div>

        <div class="alert alert--warn">
            <strong>Un último paso, importante:</strong> borra ahora el archivo
            <code>public/install.php</code> del servidor. Mientras siga ahí, cualquiera
            podría reinstalar la tienda.
        </div>

        <h3>Tus enlaces</h3>
        <p>
            Tienda: <a href="./">ir a la web</a><br>
            Panel de gestión: <a href="./admin">ir al panel</a>
        </p>

    <?php elseif ($alreadyInstalled): ?>
        <h2>Ya estaba instalada</h2>

        <div class="alert alert--warn">
            Esta tienda ya se instaló. Por seguridad, el instalador no vuelve a ejecutarse.
        </div>

        <p>
            Borra <code>public/install.php</code> del servidor. Si de verdad necesitas
            reinstalar desde cero, elimina también <code>config/installed.lock</code>.
        </p>

        <p><a href="./admin">Ir al panel de gestión</a></p>

    <?php else: ?>
        <h2>Vamos a poner la tienda en marcha</h2>
        <p>Dos minutos. Sólo necesitas los datos de la base de datos que te da tu alojamiento.</p>

        <h3>Comprobaciones del servidor</h3>
        <ul class="checks">
            <?php foreach ($checks as $label => $pass): ?>
                <li class="<?= $pass ? 'ok' : 'bad' ?>">
                    <?= $pass ? '&#10003;' : '&#10007;' ?> <?= esc($label) ?>
                </li>
            <?php endforeach; ?>
        </ul>

        <?php if (!$ready): ?>
            <div class="alert">
                Hay algo que el servidor no cumple. Si son permisos de carpeta, dales permiso
                de escritura (755) desde el gestor de archivos de tu alojamiento.
            </div>
        <?php endif; ?>

        <?php foreach ($errors as $error): ?>
            <div class="alert"><?= esc($error) ?></div>
        <?php endforeach; ?>

        <form method="post">
            <input type="hidden" name="token" value="<?= esc($token) ?>">

            <h3>Base de datos</h3>

            <div class="grid">
                <div>
                    <label for="db_host">Servidor</label>
                    <input type="text" id="db_host" name="db_host"
                           value="<?= esc($_POST['db_host'] ?? 'localhost') ?>">
                </div>
                <div>
                    <label for="db_port">Puerto</label>
                    <input type="number" id="db_port" name="db_port"
                           value="<?= esc((string) ($_POST['db_port'] ?? '3306')) ?>">
                </div>
            </div>

            <label for="db_name">Nombre de la base de datos</label>
            <input type="text" id="db_name" name="db_name" required
                   value="<?= esc($_POST['db_name'] ?? '') ?>">
            <p class="hint">Si no existe, la creamos nosotras.</p>

            <div class="grid">
                <div>
                    <label for="db_user">Usuario</label>
                    <input type="text" id="db_user" name="db_user"
                           value="<?= esc($_POST['db_user'] ?? '') ?>">
                </div>
                <div>
                    <label for="db_pass">Contraseña</label>
                    <input type="password" id="db_pass" name="db_pass" autocomplete="off">
                </div>
            </div>

            <h3>Tu cuenta de acceso al panel</h3>

            <label for="admin_name">Nombre</label>
            <input type="text" id="admin_name" name="admin_name" required
                   value="<?= esc($_POST['admin_name'] ?? '') ?>">

            <label for="admin_email">Correo electrónico</label>
            <input type="email" id="admin_email" name="admin_email" required autocomplete="off"
                   value="<?= esc($_POST['admin_email'] ?? '') ?>">

            <label for="admin_pass">Contraseña</label>
            <input type="password" id="admin_pass" name="admin_pass" required minlength="8" autocomplete="new-password">
            <p class="hint">Mínimo 8 caracteres. Apúntala: es con la que entrarás al panel.</p>

            <button type="submit" <?= $ready ? '' : 'disabled' ?>>Instalar la tienda</button>
        </form>
    <?php endif; ?>
</div>
</body>
</html>
