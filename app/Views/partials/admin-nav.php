<?php

/**
 * Navegación del panel. Cada icono es un trazo simple y el texto, directo:
 * la persona que lo usa a diario no tiene por qué saber de informática.
 */

$icons = [
    'home'     => '<path d="M4 10.5L12 4l8 6.5V20H4z"/>',
    'bag'      => '<path d="M6 7h12l-1 13H7L6 7z"/><path d="M9 7V5a3 3 0 0 1 6 0v2"/>',
    'tag'      => '<path d="M4 4h7l9 9-7 7-9-9z"/><circle cx="8.5" cy="8.5" r="1.3"/>',
    'boxes'    => '<rect x="3" y="4" width="18" height="7" rx="1"/><rect x="3" y="13" width="18" height="7" rx="1"/>',
    'folder'   => '<path d="M3 7h6l2 2.5h10V19H3z"/>',
    'ticket'   => '<path d="M3 9a2 2 0 0 0 0 6v3h18v-3a2 2 0 0 1 0-6V6H3z"/><path d="M14 6v12" stroke-dasharray="2 3"/>',
    'truck'    => '<path d="M3 7h11v9H3z"/><path d="M14 11h4l3 3v2h-7z"/><circle cx="7" cy="18" r="1.6"/><circle cx="17.5" cy="18" r="1.6"/>',
    'file'     => '<path d="M6 3h8l4 4v14H6z"/><path d="M14 3v4h4"/>',
    'pen'      => '<path d="M4 20l1-4L17 4l3 3L8 19z"/>',
    'help'     => '<circle cx="12" cy="12" r="8.5"/><path d="M9.6 9.6a2.5 2.5 0 1 1 3.3 2.4c-.6.3-.9.8-.9 1.4v.4"/><circle cx="12" cy="17" r=".6" fill="currentColor"/>',
    'ruler'    => '<rect x="2.5" y="8" width="19" height="8" rx="1"/><path d="M7 8v3M11 8v4M15 8v3M19 8v4"/>',
    'text'     => '<path d="M4 6h16M4 11h16M4 16h10"/>',
    'image'    => '<rect x="3" y="5" width="18" height="14" rx="1.5"/><circle cx="8.5" cy="10" r="1.6"/><path d="M4 17l5-5 4 4 3-2.5 4 3.5"/>',
    'mail'     => '<rect x="3" y="5" width="18" height="14" rx="1.5"/><path d="M3.5 6.5L12 13l8.5-6.5"/>',
    'heart'    => '<path d="M12 20s-7-4.4-7-9a4 4 0 0 1 7-2.6A4 4 0 0 1 19 11c0 4.6-7 9-7 9z"/>',
    'gear'     => '<circle cx="12" cy="12" r="3"/><path d="M12 3v2.4M12 18.6V21M21 12h-2.4M5.4 12H3M18.4 5.6l-1.7 1.7M7.3 16.7l-1.7 1.7M18.4 18.4l-1.7-1.7M7.3 7.3L5.6 5.6"/>',
    'palette'  => '<path d="M12 3.5a8.5 8.5 0 1 0 0 17c1.2 0 1.8-.8 1.8-1.6 0-1.1-1-1.5-1-2.5 0-.8.7-1.4 1.6-1.4h1.4A4.7 4.7 0 0 0 20.5 10c0-3.6-3.8-6.5-8.5-6.5z"/><circle cx="8" cy="10" r="1"/><circle cx="12" cy="7.6" r="1"/><circle cx="15.8" cy="10" r="1"/>',
    'globe'    => '<circle cx="12" cy="12" r="8.5"/><path d="M3.5 12h17M12 3.5c2.3 2.4 3.4 5.3 3.4 8.5S14.3 18.1 12 20.5c-2.3-2.4-3.4-5.3-3.4-8.5S9.7 5.9 12 3.5z"/>',
    'users'    => '<circle cx="9" cy="9" r="3"/><path d="M3.5 19a5.5 5.5 0 0 1 11 0"/><path d="M16 6.5a3 3 0 0 1 0 5.8M17 19a5.5 5.5 0 0 0-1.6-3.9"/>',
    'clock'    => '<circle cx="12" cy="12" r="8.5"/><path d="M12 7.5V12l3 1.8"/>',
];

$menu = [
    '' => [
        ['path' => '',           'label' => 'Escritorio',          'icon' => 'home'],
    ],
    'Venta' => [
        ['path' => 'orders',     'label' => 'Pedidos',             'icon' => 'bag',   'badge' => 'orders'],
        ['path' => 'products',   'label' => 'Productos',           'icon' => 'tag'],
        ['path' => 'stock',      'label' => 'Existencias',         'icon' => 'boxes'],
        ['path' => 'categories', 'label' => 'Categorías',          'icon' => 'folder'],
        ['path' => 'coupons',    'label' => 'Códigos de descuento','icon' => 'ticket'],
        ['path' => 'shipping',   'label' => 'Gastos de envío',     'icon' => 'truck'],
    ],
    'Contenido' => [
        ['path' => 'pages',      'label' => 'Páginas',             'icon' => 'file'],
        ['path' => 'posts',      'label' => 'Journal',             'icon' => 'pen'],
        ['path' => 'faq',        'label' => 'Preguntas frecuentes','icon' => 'help'],
        ['path' => 'sizes',      'label' => 'Guía de tallas',      'icon' => 'ruler'],
        ['path' => 'texts',      'label' => 'Textos de la web',    'icon' => 'text'],
        ['path' => 'media',      'label' => 'Imágenes',            'icon' => 'image'],
    ],
    'Clientas' => [
        ['path' => 'messages',   'label' => 'Mensajes',            'icon' => 'mail',  'badge' => 'messages'],
        ['path' => 'subscribers','label' => 'Suscriptoras',        'icon' => 'heart'],
    ],
    'Configuración' => [
        ['path' => 'settings',   'label' => 'Ajustes',             'icon' => 'gear'],
        ['path' => 'themes',     'label' => 'Aspecto de la web',   'icon' => 'palette'],
        ['path' => 'languages',  'label' => 'Idiomas',             'icon' => 'globe'],
        ['path' => 'users',      'label' => 'Cuentas de acceso',   'icon' => 'users'],
        ['path' => 'activity',   'label' => 'Actividad',           'icon' => 'clock'],
    ],
];

$base = rtrim(admin_url(''), '/');

$isActive = static function (string $path) use ($currentPath): bool {
    $target = '/admin' . ($path === '' ? '' : '/' . $path);

    if ($path === '') {
        return $currentPath === '/admin';
    }

    return $currentPath === $target || str_starts_with($currentPath, $target . '/');
};
?>
<nav class="side" aria-label="Secciones del panel">
    <a class="side__brand" href="<?= e(admin_url()) ?>">
        <img src="<?= e(asset('img/brand/Wordmark_Oro_Trasparente.svg')) ?>" alt="<?= e($siteName) ?>" width="150" height="24">
        <span>Panel de gestión</span>
    </a>

    <?php foreach ($menu as $group => $items): ?>
        <div class="side__group">
            <?php if ($group !== ''): ?>
                <p class="side__title"><?= e($group) ?></p>
            <?php endif; ?>

            <?php foreach ($items as $item): ?>
                <?php $count = isset($item['badge']) ? (int) ($badges[$item['badge']] ?? 0) : 0; ?>
                <a class="side__link <?= $isActive($item['path']) ? 'is-active' : '' ?>"
                   href="<?= e($base . ($item['path'] === '' ? '' : '/' . $item['path'])) ?>"
                   <?= $isActive($item['path']) ? 'aria-current="page"' : '' ?>>
                    <svg viewBox="0 0 24 24" aria-hidden="true"><?= $icons[$item['icon']] ?></svg>
                    <?= e($item['label']) ?>
                    <?php if ($count > 0): ?>
                        <span class="side__badge"><?= $count ?></span>
                    <?php endif; ?>
                </a>
            <?php endforeach; ?>
        </div>
    <?php endforeach; ?>
</nav>
