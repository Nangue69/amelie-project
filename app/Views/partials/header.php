<?php

use App\Core\Seo;

$currentPath = Seo::canonicalPath();

$navItems = [
    ['path' => 'shop',      'label' => t('nav.shop')],
    ['path' => 'our-story', 'label' => t('nav.story')],
    ['path' => 'journal',   'label' => t('nav.journal')],
    ['path' => 'contact',   'label' => t('nav.contact')],
];

$isCurrent = static function (string $path) use ($currentPath): bool {
    return $currentPath === $path || str_starts_with($currentPath, $path . '/');
};
?>
<p class="announce"><?= e(t('footer.newsletter_text')) ?></p>

<header class="header">
    <div class="wrap header__inner">
        <button type="button" class="icon-btn burger" data-open="menu-drawer" aria-label="<?= e(t('nav.menu')) ?>">
            <svg viewBox="0 0 24 24" aria-hidden="true"><path d="M3 6h18M3 12h18M3 18h18"/></svg>
        </button>

        <nav class="header__nav" aria-label="<?= e(t('nav.menu')) ?>">
            <?php foreach ($navItems as $item): ?>
                <a class="nav-link" href="<?= e(lang_url($item['path'])) ?>"
                   <?= $isCurrent($item['path']) ? 'aria-current="page"' : '' ?>><?= e($item['label']) ?></a>
            <?php endforeach; ?>
        </nav>

        <a class="header__logo" href="<?= e(lang_url('')) ?>" aria-label="<?= e($siteName) ?>">
            <img src="<?= e(asset('img/brand/Wordmark_Oro_Trasparente.svg')) ?>"
                 alt="<?= e($siteName . ' — ' . $siteTagline) ?>" width="200" height="32">
        </a>

        <div class="header__tools">
            <div class="lang">
                <button type="button" class="lang__toggle" aria-expanded="false" aria-haspopup="true">
                    <?= e(strtoupper($lang)) ?>
                    <svg width="10" height="7" viewBox="0 0 12 8" aria-hidden="true">
                        <path d="M1 1l5 5 5-5" fill="none" stroke="currentColor" stroke-width="1.4"/>
                    </svg>
                </button>
                <ul class="lang__menu" hidden>
                    <?php foreach ($languages as $code => $language): ?>
                        <li>
                            <a href="<?= e(lang_url($currentPath, $code)) ?>" lang="<?= e($code) ?>"
                               <?= $code === $lang ? 'aria-current="true"' : '' ?>><?= e($language['native_name']) ?></a>
                        </li>
                    <?php endforeach; ?>
                </ul>
            </div>

            <button type="button" class="icon-btn" data-search-toggle aria-expanded="false"
                    aria-controls="searchbar" aria-label="<?= e(t('nav.search')) ?>">
                <svg viewBox="0 0 24 24" aria-hidden="true">
                    <circle cx="11" cy="11" r="7"/><path d="M20 20l-4.2-4.2"/>
                </svg>
            </button>

            <a class="icon-btn" href="<?= e(lang_url('cart')) ?>" aria-label="<?= e(t('nav.cart')) ?>">
                <svg viewBox="0 0 24 24" aria-hidden="true">
                    <path d="M6 7h12l-1 13H7L6 7z"/><path d="M9 7V5a3 3 0 0 1 6 0v2"/>
                </svg>
                <span class="cart-count" data-cart-count <?= $cartCount < 1 ? 'hidden' : '' ?>><?= (int) $cartCount ?></span>
            </a>
        </div>
    </div>

    <div class="searchbar" id="searchbar" hidden>
        <form class="wrap" action="<?= e(lang_url('search')) ?>" method="get" role="search">
            <label class="visually-hidden" for="site-search"><?= e(t('nav.search')) ?></label>
            <input class="input" type="search" id="site-search" name="q"
                   placeholder="<?= e(t('search.placeholder')) ?>" autocomplete="off">
            <button class="btn" type="submit"><?= e(t('nav.search')) ?></button>
        </form>
    </div>
</header>

<div class="drawer" id="menu-drawer" role="dialog" aria-modal="true" aria-label="<?= e(t('nav.menu')) ?>" hidden>
    <button type="button" class="drawer__backdrop" data-close aria-label="<?= e(t('common.close')) ?>"></button>
    <div class="drawer__panel">
        <div class="drawer__head">
            <p class="drawer__title"><?= e(t('nav.menu')) ?></p>
            <button type="button" class="modal__close" data-close
                    style="position:static;" aria-label="<?= e(t('common.close')) ?>">&times;</button>
        </div>

        <nav class="drawer__nav" aria-label="<?= e(t('nav.menu')) ?>">
            <?php foreach ($navItems as $item): ?>
                <a href="<?= e(lang_url($item['path'])) ?>"><?= e($item['label']) ?></a>
            <?php endforeach; ?>
            <a href="<?= e(lang_url('cart')) ?>"><?= e(t('nav.cart')) ?></a>
        </nav>

        <div class="drawer__langs">
            <?php foreach ($languages as $code => $language): ?>
                <a href="<?= e(lang_url($currentPath, $code)) ?>" lang="<?= e($code) ?>"
                   <?= $code === $lang ? 'aria-current="true"' : '' ?>><?= e($language['native_name']) ?></a>
            <?php endforeach; ?>
        </div>
    </div>
</div>
