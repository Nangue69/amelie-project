<?php

use App\Core\Csrf;
use App\Core\Flash;
use App\Core\Seo;
use App\Core\Settings;
use App\Core\View;

$flashes    = Flash::pull();
$alternates = Seo::alternates();

$appConfig = [
    'csrf'        => Csrf::token(),
    'lang'        => $lang,
    'lowStock'    => Settings::int('low_stock_threshold', 5),
    'analyticsId' => Settings::get('analytics_id'),
    'routes'      => [
        'cartAdd'    => lang_url('cart/add'),
        'cartUpdate' => lang_url('cart/update'),
        'cartRemove' => lang_url('cart/remove'),
    ],
    'strings'     => [
        'add_to_cart'       => t('product.add'),
        'sold_out'          => t('product.sold_out'),
        'select_size'       => t('product.select_size'),
        'choose_both'       => t('product.choose_both'),
        'select_size_first' => t('msg.select_size_first'),
        'in_stock'          => t('product.in_stock'),
        'low_stock'         => t('product.low_stock'),
        'out_of_stock'      => t('product.out_of_stock'),
        'remove'            => t('cart.remove'),
        'cart_empty'        => t('cart.empty'),
        'invalid_email'     => t('msg.invalid_email'),
        'error'             => t('msg.error'),
        'close'             => t('common.close'),
        'cookies_saved'     => t('msg.preferences_saved'),
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
<?php if (Seo::noindex()): ?>
<meta name="robots" content="noindex, nofollow">
<?php else: ?>
<meta name="robots" content="index, follow, max-image-preview:large">
<?php endif; ?>
<link rel="canonical" href="<?= e(Seo::canonical()) ?>">
<?php foreach ($alternates as $code => $href): ?>
<link rel="alternate" hreflang="<?= e($code) ?>" href="<?= e($href) ?>">
<?php endforeach; ?>

<meta property="og:type" content="<?= e(Seo::type()) ?>">
<meta property="og:site_name" content="<?= e($siteName) ?>">
<meta property="og:title" content="<?= e(Seo::title()) ?>">
<meta property="og:description" content="<?= e(Seo::description()) ?>">
<meta property="og:url" content="<?= e(Seo::canonical()) ?>">
<meta property="og:image" content="<?= e(Seo::image()) ?>">
<meta property="og:locale" content="<?= e($lang) ?>">
<meta name="twitter:card" content="summary_large_image">

<meta name="theme-color" content="#651D36">
<link rel="icon" href="<?= e(asset('img/brand/Monogramma_AM_Oro_su_Avorio.svg')) ?>" type="image/svg+xml">
<link rel="apple-touch-icon" href="<?= e(asset('img/brand/Monogramma_AM_Oro_su_Avorio.png')) ?>">

<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Cormorant+Garamond:ital,wght@0,300;0,400;0,500;1,300;1,400&family=Jost:wght@200;300;400;500&display=swap">

<link rel="stylesheet" href="<?= e(asset('css/base.css')) ?>">
<link rel="stylesheet" href="<?= e(asset('css/themes/' . $themeKey . '.css')) ?>">
<link rel="stylesheet" href="<?= e(asset('css/site.css')) ?>">

<?php foreach (Seo::schemas() as $schema): ?>
<script type="application/ld+json"><?= json_encode($schema, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES) ?></script>
<?php endforeach; ?>
<script type="application/json" id="app-config"><?= json_encode($appConfig, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES) ?></script>
</head>
<body>
<a class="skip-link" href="#main"><?= e(t('common.home')) ?></a>

<?= View::partial('header') ?>

<main id="main">
<?= View::section('content') ?>
</main>

<?= View::partial('footer') ?>
<?= View::partial('cart-drawer') ?>
<?= View::partial('cookies') ?>

<?php foreach ($flashes as $flash): ?>
<div data-flash="<?= e($flash['type'] === 'success' ? 'ok' : $flash['type']) ?>" data-flash-message="<?= e($flash['message']) ?>" hidden></div>
<?php endforeach; ?>

<script src="<?= e(asset('js/site.js')) ?>" defer></script>
</body>
</html>
