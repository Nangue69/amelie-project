<?php

use App\Core\View;

/** @var array $flashes */
?>
<!doctype html>
<html lang="es">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<meta name="robots" content="noindex, nofollow">
<title>Acceso · <?= e($siteName) ?></title>
<link rel="icon" href="<?= e(asset('img/brand/Monogramma_AM_Oro_su_Avorio.svg')) ?>" type="image/svg+xml">
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Cormorant+Garamond:wght@300;400&family=Jost:wght@300;400;500&display=swap">
<link rel="stylesheet" href="<?= e(asset('css/base.css')) ?>">
<link rel="stylesheet" href="<?= e(asset('css/admin.css')) ?>">
</head>
<body class="login-page">
<?= View::section('content') ?>

<?php foreach ($flashes as $flash): ?>
<div data-flash="<?= e($flash['type'] === 'success' ? 'ok' : $flash['type']) ?>" data-flash-message="<?= e($flash['message']) ?>" hidden></div>
<?php endforeach; ?>

<script src="<?= e(asset('js/admin.js')) ?>" defer></script>
</body>
</html>
