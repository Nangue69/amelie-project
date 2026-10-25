<?php

use App\Core\Flash;
use App\Core\Lang;
use App\Core\View;

$flashes = Flash::pull();
$initial = mb_strtoupper(mb_substr((string) ($user['name'] ?? 'A'), 0, 1));
?>
<!doctype html>
<html lang="es">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<meta name="robots" content="noindex, nofollow">
<title><?= e($pageTitle ?? 'Panel') ?> · <?= e($siteName) ?></title>

<link rel="icon" href="<?= e(asset('img/brand/Monogramma_AM_Oro_su_Avorio.svg')) ?>" type="image/svg+xml">
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Cormorant+Garamond:wght@300;400&family=Jost:wght@300;400;500&display=swap">
<link rel="stylesheet" href="<?= e(asset('css/base.css')) ?>">
<link rel="stylesheet" href="<?= e(asset('css/admin.css')) ?>">
</head>
<body class="admin">

<?= View::partial('admin-nav') ?>

<div class="main">
    <header class="topbar">
        <button type="button" class="side-toggle" data-side-toggle aria-label="Abrir el menú">
            <svg viewBox="0 0 24 24" aria-hidden="true"><path d="M3 6h18M3 12h18M3 18h18"/></svg>
        </button>

        <h1 class="topbar__title"><?= e($pageTitle ?? 'Panel') ?></h1>

        <div class="topbar__spacer"></div>

        <a class="abtn abtn--ghost abtn--sm" href="<?= e(lang_url('', Lang::default())) ?>" target="_blank" rel="noopener">
            Ver la web
        </a>

        <div class="topbar__user">
            <span><?= e((string) ($user['name'] ?? '')) ?></span>
            <span class="topbar__avatar" aria-hidden="true"><?= e($initial) ?></span>
        </div>

        <form action="<?= e(admin_url('logout')) ?>" method="post">
            <?= csrf_field() ?>
            <button class="abtn abtn--ghost abtn--sm" type="submit">Salir</button>
        </form>
    </header>

    <main class="content">
        <?= View::section('content') ?>
    </main>
</div>

<?php foreach ($flashes as $flash): ?>
<div data-flash="<?= e($flash['type'] === 'success' ? 'ok' : $flash['type']) ?>" data-flash-message="<?= e($flash['message']) ?>" hidden></div>
<?php endforeach; ?>

<script src="<?= e(asset('js/admin.js')) ?>" defer></script>
</body>
</html>
