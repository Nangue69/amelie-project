<?php

declare(strict_types=1);

use App\Controllers\Admin\AuthController;
use App\Controllers\Admin\CatalogController;
use App\Controllers\Admin\ContentController;
use App\Controllers\Admin\DashboardController;
use App\Controllers\Admin\OrdersController;
use App\Controllers\Admin\SettingsController;
use App\Controllers\Site\CartController;
use App\Controllers\Site\CheckoutController;
use App\Controllers\Site\HomeController;
use App\Controllers\Site\PageController;
use App\Controllers\Site\ShopController;
use App\Core\Lang;
use App\Core\Router;

$router = new Router();

// ---------------------------------------------------------------------
// Raiz y archivos de indexacion
// ---------------------------------------------------------------------
$router->get('/', static function (): void {
    header('Location: ' . lang_url('', Lang::detectFromBrowser()), true, 302);
    exit;
});

$router->get('/sitemap.xml', [PageController::class, 'sitemap']);
$router->get('/robots.txt', [PageController::class, 'robots']);

// ---------------------------------------------------------------------
// Panel de administracion
// ---------------------------------------------------------------------
$router->get('/admin', [DashboardController::class, 'index']);
$router->get('/admin/login', [AuthController::class, 'showLogin']);
$router->post('/admin/login', [AuthController::class, 'login']);
$router->post('/admin/logout', [AuthController::class, 'logout']);

$router->get('/admin/products', [CatalogController::class, 'products']);
$router->get('/admin/products/new', [CatalogController::class, 'createProduct']);
$router->get('/admin/products/{id:[0-9]+}', [CatalogController::class, 'editProduct']);
$router->post('/admin/products/{id:[0-9]+}', [CatalogController::class, 'saveProduct']);
$router->post('/admin/products/new', [CatalogController::class, 'storeProduct']);
$router->post('/admin/products/{id:[0-9]+}/delete', [CatalogController::class, 'deleteProduct']);
$router->post('/admin/products/{id:[0-9]+}/images', [CatalogController::class, 'uploadImage']);
$router->post('/admin/products/{id:[0-9]+}/images/{imageId:[0-9]+}/delete', [CatalogController::class, 'deleteImage']);
$router->post('/admin/products/{id:[0-9]+}/images/{imageId:[0-9]+}/primary', [CatalogController::class, 'makePrimary']);
$router->post('/admin/products/{id:[0-9]+}/variants', [CatalogController::class, 'saveVariants']);
$router->post('/admin/products/{id:[0-9]+}/variants/new', [CatalogController::class, 'addVariant']);
$router->post('/admin/products/{id:[0-9]+}/variants/{variantId:[0-9]+}/delete', [CatalogController::class, 'deleteVariant']);

$router->get('/admin/stock', [CatalogController::class, 'stock']);
$router->post('/admin/stock', [CatalogController::class, 'saveStock']);

$router->get('/admin/categories', [CatalogController::class, 'categories']);
$router->post('/admin/categories', [CatalogController::class, 'saveCategories']);

$router->get('/admin/orders', [OrdersController::class, 'index']);
$router->get('/admin/orders/{id:[0-9]+}', [OrdersController::class, 'show']);
$router->post('/admin/orders/{id:[0-9]+}', [OrdersController::class, 'update']);

$router->get('/admin/pages', [ContentController::class, 'pages']);
$router->get('/admin/pages/{id:[0-9]+}', [ContentController::class, 'editPage']);
$router->post('/admin/pages/{id:[0-9]+}', [ContentController::class, 'savePage']);

$router->get('/admin/posts', [ContentController::class, 'posts']);
$router->get('/admin/posts/new', [ContentController::class, 'createPost']);
$router->post('/admin/posts/new', [ContentController::class, 'storePost']);
$router->get('/admin/posts/{id:[0-9]+}', [ContentController::class, 'editPost']);
$router->post('/admin/posts/{id:[0-9]+}', [ContentController::class, 'savePost']);
$router->post('/admin/posts/{id:[0-9]+}/delete', [ContentController::class, 'deletePost']);

$router->get('/admin/faq', [ContentController::class, 'faqs']);
$router->post('/admin/faq', [ContentController::class, 'saveFaqs']);
$router->post('/admin/faq/new', [ContentController::class, 'addFaq']);
$router->post('/admin/faq/{id:[0-9]+}/delete', [ContentController::class, 'deleteFaq']);

$router->get('/admin/sizes', [ContentController::class, 'sizes']);
$router->post('/admin/sizes', [ContentController::class, 'saveSizes']);

$router->get('/admin/texts', [ContentController::class, 'texts']);
$router->post('/admin/texts', [ContentController::class, 'saveTexts']);

$router->get('/admin/media', [ContentController::class, 'media']);
$router->post('/admin/media', [ContentController::class, 'uploadMedia']);
$router->post('/admin/media/{id:[0-9]+}/delete', [ContentController::class, 'deleteMedia']);

$router->get('/admin/subscribers', [ContentController::class, 'subscribers']);
$router->get('/admin/subscribers/export', [ContentController::class, 'exportSubscribers']);
$router->post('/admin/subscribers/{id:[0-9]+}/delete', [ContentController::class, 'deleteSubscriber']);

$router->get('/admin/messages', [ContentController::class, 'messages']);
$router->get('/admin/messages/{id:[0-9]+}', [ContentController::class, 'showMessage']);
$router->post('/admin/messages/{id:[0-9]+}/delete', [ContentController::class, 'deleteMessage']);

$router->get('/admin/settings', [SettingsController::class, 'index']);
$router->post('/admin/settings', [SettingsController::class, 'save']);
$router->get('/admin/themes', [SettingsController::class, 'themes']);
$router->post('/admin/themes', [SettingsController::class, 'activateTheme']);
$router->get('/admin/shipping', [SettingsController::class, 'shipping']);
$router->post('/admin/shipping', [SettingsController::class, 'saveShipping']);
$router->get('/admin/coupons', [SettingsController::class, 'coupons']);
$router->post('/admin/coupons', [SettingsController::class, 'saveCoupons']);
$router->post('/admin/coupons/new', [SettingsController::class, 'addCoupon']);
$router->post('/admin/coupons/{id:[0-9]+}/delete', [SettingsController::class, 'deleteCoupon']);
$router->get('/admin/languages', [SettingsController::class, 'languages']);
$router->post('/admin/languages', [SettingsController::class, 'saveLanguages']);
$router->get('/admin/users', [SettingsController::class, 'users']);
$router->post('/admin/users', [SettingsController::class, 'saveUsers']);
$router->post('/admin/users/new', [SettingsController::class, 'addUser']);
$router->post('/admin/users/{id:[0-9]+}/delete', [SettingsController::class, 'deleteUser']);
$router->get('/admin/activity', [SettingsController::class, 'activity']);

// ---------------------------------------------------------------------
// Parte publica
// ---------------------------------------------------------------------
$router->get('/{lang:[a-z][a-z]}', [HomeController::class, 'index']);
$router->get('/{lang:[a-z][a-z]}/shop', [ShopController::class, 'index']);
$router->get('/{lang:[a-z][a-z]}/shop/{category}', [ShopController::class, 'index']);
$router->get('/{lang:[a-z][a-z]}/product/{slug}', [ShopController::class, 'product']);
$router->get('/{lang:[a-z][a-z]}/search', [ShopController::class, 'search']);

$router->get('/{lang:[a-z][a-z]}/journal', [PageController::class, 'journal']);
$router->get('/{lang:[a-z][a-z]}/journal/{slug}', [PageController::class, 'post']);

$router->get('/{lang:[a-z][a-z]}/cart', [CartController::class, 'index']);
$router->post('/{lang:[a-z][a-z]}/cart/add', [CartController::class, 'add']);
$router->post('/{lang:[a-z][a-z]}/cart/update', [CartController::class, 'update']);
$router->post('/{lang:[a-z][a-z]}/cart/remove', [CartController::class, 'remove']);
$router->post('/{lang:[a-z][a-z]}/cart/coupon', [CartController::class, 'coupon']);

$router->get('/{lang:[a-z][a-z]}/checkout', [CheckoutController::class, 'index']);
$router->post('/{lang:[a-z][a-z]}/checkout', [CheckoutController::class, 'store']);
$router->get('/{lang:[a-z][a-z]}/checkout/success/{number}', [CheckoutController::class, 'success']);

$router->post('/{lang:[a-z][a-z]}/newsletter', [PageController::class, 'newsletterSubmit']);
$router->post('/{lang:[a-z][a-z]}/contact', [PageController::class, 'contactSubmit']);
$router->get('/{lang:[a-z][a-z]}/unsubscribe/{token}', [PageController::class, 'unsubscribe']);

// Paginas de contenido. Debe quedar la ultima: captura cualquier slug.
$router->get('/{lang:[a-z][a-z]}/{slug}', [PageController::class, 'show']);

$router->fallback([PageController::class, 'show']);

return $router;
