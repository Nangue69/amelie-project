<?php

declare(strict_types=1);

return [
    'db' => [
        'host'    => 'localhost',
        'port'    => 3306,
        'name'    => 'amelie',
        'user'    => 'root',
        'pass'    => '',
        'charset' => 'utf8mb4',
    ],
    'app' => [
        'debug'    => false,
        'timezone' => 'Europe/Madrid',
        'key'      => 'cambia-esta-cadena-por-una-aleatoria',
    ],
    'mail' => [
        'from_name'  => 'AMÉLIE',
        'from_email' => 'hola@amelie.com',
    ],
];
