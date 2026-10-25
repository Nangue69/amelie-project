<?php

use App\Core\Csrf;
use App\Core\Flash;
use App\Core\Seo;
use App\Core\Settings;
use App\Core\View;

$flashes = Flash::pull();

$appConfig = [
    'csrf'    => Csrf::token(),
    'lang'    => $lang,
    'routes'  => [],
    'strings' => [
        'invalid_email' => t('msg.invalid_email'),
        'error'         => t('msg.error'),
        'close'         => t('common.close'),
    ],
];
?>
<!doctype html>
<html lang="<?= e($lang) ?>">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title><?= e(Seo::title()) ?></title>
<meta name="description" content="<?= e(Seo::description()) ?>">
<meta name="robots" content="noindex, follow">
<link rel="canonical" href="<?= e(Seo::canonical()) ?>">
<?php foreach (Seo::alternates() as $code => $href): ?>
<link rel="alternate" hreflang="<?= e($code) ?>" href="<?= e($href) ?>">
<?php endforeach; ?>

<meta property="og:type" content="website">
<meta property="og:title" content="<?= e(Seo::title()) ?>">
<meta property="og:description" content="<?= e(Seo::description()) ?>">
<meta property="og:image" content="<?= e(Seo::image()) ?>">
<meta name="theme-color" content="#651D36">
<link rel="icon" href="<?= e(asset('img/brand/Monogramma_AM_Oro_su_Avorio.svg')) ?>" type="image/svg+xml">

<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Cormorant+Garamond:ital,wght@0,300;0,400;1,300&family=Jost:wght@200;300;400&display=swap">

<link rel="stylesheet" href="<?= e(asset('css/base.css')) ?>">
<link rel="stylesheet" href="<?= e(asset('css/themes/' . $themeKey . '.css')) ?>">
<link rel="stylesheet" href="<?= e(asset('css/site.css')) ?>">

<script type="application/json" id="app-config"><?= json_encode($appConfig, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES) ?></script>
</head>
<body>
<main>
<?= View::section('content') ?>
</main>

<?php foreach ($flashes as $flash): ?>
<div data-flash="<?= e($flash['type'] === 'success' ? 'ok' : $flash['type']) ?>" data-flash-message="<?= e($flash['message']) ?>" hidden></div>
<?php endforeach; ?>

<script src="<?= e(asset('js/site.js')) ?>" defer></script>
</body>
</html>
