<?php

/**
 * Router para el servidor integrado de PHP (desarrollo local, sin Apache):
 *
 *   php -S localhost:8080 -t public public/router-dev.php
 *
 * En produccion no se usa: Apache entra por index.php con .htaccess.
 */

declare(strict_types=1);

$path = parse_url($_SERVER['REQUEST_URI'] ?? '/', PHP_URL_PATH) ?: '/';
$file = __DIR__ . $path;

if ($path === '/' || !is_file($file)) {
    require __DIR__ . '/index.php';

    return true;
}

// Los scripts sueltos (install.php) se ejecutan; el resto se sirve tal cual.
if (str_ends_with($path, '.php')) {
    if (in_array(basename($path), ['index.php', 'router-dev.php'], true)) {
        require __DIR__ . '/index.php';

        return true;
    }

    require $file;

    return true;
}

return false;
