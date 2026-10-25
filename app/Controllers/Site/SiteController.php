<?php

declare(strict_types=1);

namespace App\Controllers\Site;

use App\Core\Cart;
use App\Core\Controller;
use App\Core\Lang;
use App\Core\Request;
use App\Core\Seo;
use App\Core\Settings;
use App\Core\View;
use App\Models\Category;
use App\Models\Page;
use App\Models\Theme;

abstract class SiteController extends Controller
{
    protected string $lang = 'es';

    /**
     * Fija el idioma de la peticion y comparte lo que necesita el layout.
     */
    protected function boot(Request $request): void
    {
        $this->lang = $request->param('lang', Lang::default());

        Lang::set($this->lang);
        $this->lang = Lang::current();

        Seo::reset();
        Seo::addSchema(Seo::organizationSchema());

        View::share('lang', $this->lang);
        View::share('languages', Lang::available());
        View::share('categories', Category::active($this->lang));
        View::share('footerLinks', Page::footerLinks($this->lang));
        View::share('cartCount', Cart::count());
        View::share('themeKey', Theme::activeKey());
        View::share('siteName', Settings::get('site_name', 'AMÉLIE'));
        View::share('siteTagline', Settings::get('site_tagline', 'More Than Lingerie'));
    }

    /**
     * Con el modo Coming Soon activo, la tienda se repliega a la portada.
     * Se deja pasar la suscripcion, que es lo unico que debe funcionar.
     */
    protected function guardComingSoon(Request $request): void
    {
        if (!Settings::bool('coming_soon')) {
            return;
        }

        if ($request->isPost()) {
            return;
        }

        $this->redirect(lang_url(''));
    }

    /**
     * Pagina 404 con el estilo de la tienda.
     */
    protected function renderNotFound(): void
    {
        http_response_code(404);

        Seo::set([
            'title'       => t('error.404.title'),
            'description' => t('error.404.text'),
            'noindex'     => true,
            'path'        => '',
        ]);

        $this->view('site/not-found');
    }

    protected function productUrl(string $slug): string
    {
        return lang_url('product/' . $slug);
    }
}
