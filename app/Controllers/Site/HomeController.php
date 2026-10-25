<?php

declare(strict_types=1);

namespace App\Controllers\Site;

use App\Core\Request;
use App\Core\Seo;
use App\Core\Settings;
use App\Models\Post;
use App\Models\Product;

final class HomeController extends SiteController
{
    public function index(Request $request): void
    {
        $this->boot($request);

        if (Settings::bool('coming_soon')) {
            $this->comingSoon();

            return;
        }

        // Sin titulo propio: la portada usa el sufijo de marca tal cual.
        Seo::set([
            'title'       => '',
            'description' => t('home.hero.line1') . ' ' . t('home.hero.line2'),
            'image'       => Settings::get('home_hero_image'),
            'path'        => '',
        ]);

        $this->view('site/home', [
            'products' => Product::featured($this->lang, 4),
            'posts'    => Post::published($this->lang, 3),
        ]);
    }

    private function comingSoon(): void
    {
        Seo::set([
            'title'       => t('coming.title'),
            'description' => t('coming.text'),
            'image'       => Settings::get('home_hero_image'),
            'path'        => '',
        ]);

        echo \App\Core\View::render('site/coming-soon', [], 'minimal');
    }
}
