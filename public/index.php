<?php

declare(strict_types=1);

require dirname(__DIR__) . '/app/bootstrap.php';

use App\Core\Request;

header('X-Content-Type-Options: nosniff');
header('X-Frame-Options: SAMEORIGIN');
header('Referrer-Policy: strict-origin-when-cross-origin');
header('Permissions-Policy: geolocation=(), microphone=(), camera=()');

$router  = require APP_PATH . '/routes.php';
$request = new Request();

try {
    $router->dispatch($request);
} catch (Throwable $e) {
    error_log('[AMELIE] ' . $e->getMessage() . ' en ' . $e->getFile() . ':' . $e->getLine());

    if (App\Core\Config::get('app.debug')) {
        http_response_code(500);
        echo '<pre style="padding:24px;font:14px/1.5 monospace;background:#2A2024;color:#F6F0E6;">';
        echo htmlspecialchars($e->getMessage(), ENT_QUOTES, 'UTF-8') . "\n\n";
        echo htmlspecialchars($e->getTraceAsString(), ENT_QUOTES, 'UTF-8');
        echo '</pre>';
        exit;
    }

    http_response_code(500);
    echo '<!doctype html><html lang="es"><head><meta charset="utf-8">'
        . '<title>AMÉLIE</title><style>body{margin:0;min-height:100vh;display:grid;place-items:center;'
        . 'background:#F6F0E6;color:#651D36;font:16px/1.6 Georgia,serif;text-align:center;padding:24px;}'
        . 'a{color:#C4A36A;}</style></head><body><div><h1>Algo no ha salido bien</h1>'
        . '<p>Estamos trabajando en ello. Inténtalo de nuevo en unos minutos.</p>'
        . '<p><a href="' . htmlspecialchars(url('/'), ENT_QUOTES, 'UTF-8') . '">Volver al inicio</a></p>'
        . '</div></body></html>';
}
