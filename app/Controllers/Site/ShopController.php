<?php

declare(strict_types=1);

namespace App\Controllers\Site;

use App\Core\Request;
use App\Core\Seo;
use App\Models\Category;
use App\Models\Product;
use App\Models\SizeGuide;

final class ShopController extends SiteController
{
    private const SORTS = ['featured', 'newest', 'price_asc', 'price_desc'];

    public function index(Request $request): void
    {
        $this->boot($request);
        $this->guardComingSoon($request);

        $categorySlug = $request->param('category');
        $sort         = $request->text('sort', 'featured');

        if (!in_array($sort, self::SORTS, true)) {
            $sort = 'featured';
        }

        $category = null;
        $path     = 'shop';

        if ($categorySlug !== '') {
            $category = Category::findBySlug($categorySlug, $this->lang);

            if ($category === null) {
                $this->renderNotFound();

                return;
            }

            $path = 'shop/' . $categorySlug;
        }

        $products = Product::published($this->lang, $categorySlug ?: null, $sort);

        Seo::set([
            'title'       => $category['meta_title'] ?? $category['name'] ?? t('shop.title'),
            'description' => $category['meta_description'] ?? $category['description'] ?? t('shop.subtitle'),
            'path'        => $path,
        ]);

        Seo::addSchema(Seo::breadcrumbSchema([
            ['name' => t('common.home'), 'url' => lang_url('')],
            ['name' => $category['name'] ?? t('shop.title'), 'url' => lang_url($path)],
        ]));

        $this->view('site/shop', [
            'products' => $products,
            'category' => $category,
            'sort'     => $sort,
            'sorts'    => self::SORTS,
        ]);
    }

    public function product(Request $request): void
    {
        $this->boot($request);
        $this->guardComingSoon($request);

        $product = Product::findBySlug($request->param('slug'), $this->lang);

        if ($product === null) {
            $this->renderNotFound();

            return;
        }

        $productId = (int) $product['id'];
        $images    = Product::images($productId);
        $variants  = Product::variants($productId);
        $path      = 'product/' . $product['slug'];

        Seo::set([
            'title'       => $product['meta_title'] ?: $product['name'],
            'description' => $product['meta_description'] ?: excerpt($product['short_description']),
            'image'       => $images[0]['path'] ?? '',
            'type'        => 'product',
            'path'        => $path,
        ]);

        Seo::addSchema($this->productSchema($product, $images, $variants));
        Seo::addSchema(Seo::breadcrumbSchema([
            ['name' => t('common.home'), 'url' => lang_url('')],
            ['name' => t('shop.title'), 'url' => lang_url('shop')],
            ['name' => (string) $product['name'], 'url' => lang_url($path)],
        ]));

        $this->view('site/product', [
            'product'    => $product,
            'images'     => $images,
            'variants'   => $variants,
            'braSizes'   => $this->uniqueSizes($variants, 'size_bra'),
            'briefSizes' => $this->uniqueSizes($variants, 'size_brief'),
            'sizes'      => $this->uniqueSizes($variants, 'size'),
            'guides'     => SizeGuide::allWithRows(),
            'related'    => Product::related($productId, $this->lang, 2),
        ]);
    }

    public function search(Request $request): void
    {
        $this->boot($request);
        $this->guardComingSoon($request);

        $term     = trim($request->text('q'));
        $products = $term === '' ? [] : Product::search($term, $this->lang);

        Seo::set([
            'title'       => t('search.title'),
            'description' => t('search.placeholder'),
            'noindex'     => true,
            'path'        => 'search',
        ]);

        $this->view('site/search', ['products' => $products, 'term' => $term]);
    }

    /**
     * Valores distintos de una columna de talla, en el orden del catalogo.
     */
    private function uniqueSizes(array $variants, string $column): array
    {
        $sizes = [];

        foreach ($variants as $variant) {
            $value = trim((string) ($variant[$column] ?? ''));

            if ($value !== '' && !in_array($value, $sizes, true)) {
                $sizes[] = $value;
            }
        }

        return $sizes;
    }

    private function productSchema(array $product, array $images, array $variants): array
    {
        $offers = [];

        foreach ($variants as $variant) {
            $price = $variant['price_override'] !== null
                ? (float) $variant['price_override']
                : (float) $product['price'];

            $offers[] = [
                '@type'         => 'Offer',
                'sku'           => $variant['sku'],
                'price'         => number_format($price, 2, '.', ''),
                'priceCurrency' => setting('currency', 'EUR'),
                'availability'  => (int) $variant['stock'] > 0
                    ? 'https://schema.org/InStock'
                    : 'https://schema.org/OutOfStock',
                'url'           => Seo::absolute(lang_url('product/' . $product['slug'])),
            ];
        }

        $imageUrls = [];

        foreach ($images as $image) {
            $imageUrls[] = Seo::absolute(img((string) $image['path']));
        }

        return [
            '@context'    => 'https://schema.org',
            '@type'       => 'Product',
            'name'        => $product['name'],
            'description' => excerpt($product['short_description'] ?: $product['description'], 300),
            'sku'         => $product['sku'],
            'image'       => $imageUrls,
            'brand'       => ['@type' => 'Brand', 'name' => setting('site_name', 'AMÉLIE')],
            'offers'      => $offers,
        ];
    }
}
