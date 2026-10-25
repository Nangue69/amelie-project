<?php

declare(strict_types=1);

define('APP_ROOT', dirname(__DIR__));
define('APP_PATH', APP_ROOT . '/app');
define('PUBLIC_PATH', APP_ROOT . '/public');
define('STORAGE_PATH', APP_ROOT . '/storage');

$configFile = APP_ROOT . '/config/config.php';

// Sin configuracion, la tienda todavia no se ha instalado: al instalador.
if (!is_file($configFile)) {
    $installer = PUBLIC_PATH . '/install.php';

    if (is_file($installer) && PHP_SAPI !== 'cli') {
        $base = rtrim(str_replace('\\', '/', dirname((string) ($_SERVER['SCRIPT_NAME'] ?? ''))), '/');
        header('Location: ' . $base . '/install.php', true, 302);
        exit;
    }

    http_response_code(503);
    exit('AMELIE no esta instalada todavia. Abre install.php en el navegador, '
        . 'o copia config/config.example.php a config/config.php y rellena los datos de la base de datos.');
}

$config = require $configFile;

date_default_timezone_set($config['app']['timezone'] ?? 'Europe/Madrid');

if (!empty($config['app']['debug'])) {
    error_reporting(E_ALL);
    ini_set('display_errors', '1');
} else {
    error_reporting(E_ALL);
    ini_set('display_errors', '0');
    ini_set('log_errors', '1');
    ini_set('error_log', STORAGE_PATH . '/logs/php-error.log');
}

spl_autoload_register(static function (string $class): void {
    $prefix = 'App' . DIRECTORY_SEPARATOR;
    $class  = str_replace('\\', DIRECTORY_SEPARATOR, $class);

    if (!str_starts_with($class, $prefix)) {
        return;
    }

    $file = APP_PATH . DIRECTORY_SEPARATOR . substr($class, strlen($prefix)) . '.php';

    if (is_file($file)) {
        require $file;
    }
});

require APP_PATH . '/helpers.php';

App\Core\Config::load($config);
App\Core\Session::start();
App\Core\Request::sanitizeGlobals();
