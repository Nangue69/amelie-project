<?php

declare(strict_types=1);

namespace App\Controllers\Admin;

use App\Core\Auth;
use App\Core\Database;
use App\Core\Flash;
use App\Core\Lang;
use App\Core\Request;
use App\Core\Settings;
use App\Core\Uploader;
use App\Models\Category;
use App\Models\Product;

final class CatalogController extends AdminController
{
    // ------------------------------------------------------------------
    // Productos
    // ------------------------------------------------------------------

    public function products(Request $request): void
    {
        $this->boot($request);

        $this->view('admin/products', [
            'products' => Product::adminList(Lang::default()),
            'threshold'=> Settings::int('low_stock_threshold', 5),
        ]);
    }

    public function createProduct(Request $request): void
    {
        $this->boot($request);

        $this->view('admin/product-new', [
            'categories' => Category::all(Lang::default()),
        ]);
    }

    public function storeProduct(Request $request): void
    {
        $this->boot($request);
        $this->requireCsrf($request);

        $name = $request->text('name');

        if ($name === '') {
            Flash::error('El producto necesita un nombre.');
            $this->redirect(admin_url('products/new'));
        }

        $slug = $this->uniqueSlug($request->text('slug') ?: $name);

        $productId = Database::insert('products', [
            'slug'        => $slug,
            'sku'         => $request->text('sku'),
            'category_id' => $request->int('category_id') ?: null,
            'price'       => $this->decimal($request->text('price')),
            'status'      => 'draft',
            'size_mode'   => $request->text('size_mode') === 'bra_brief' ? 'bra_brief' : 'single',
            'position'    => (int) Database::value('SELECT COALESCE(MAX(`position`),0)+1 FROM `products`', [], 1),
        ]);

        foreach (array_keys($this->langs) as $code) {
            Product::saveTranslation($productId, $code, ['name' => $name]);
        }

        Auth::log('create', 'product', $productId, $name);
        $this->saved('Producto creado. Ahora completa su información.');
        $this->redirect(admin_url('products/' . $productId));
    }

    public function editProduct(Request $request): void
    {
        $this->boot($request);

        $productId = (int) $request->param('id');
        $product   = Product::find($productId);

        if ($product === null) {
            Flash::error('Ese producto no existe.');
            $this->redirect(admin_url('products'));
        }

        $this->view('admin/product-edit', [
            'product'      => $product,
            'translations' => Product::translations($productId),
            'images'       => Product::images($productId),
            'variants'     => Product::variants($productId, false),
            'categories'   => Category::all(Lang::default()),
        ]);
    }

    public function saveProduct(Request $request): void
    {
        $this->boot($request);
        $this->requireCsrf($request);

        $productId = (int) $request->param('id');

        if (Product::find($productId) === null) {
            $this->redirect(admin_url('products'));
        }

        $slug = $this->uniqueSlug($request->text('slug'), $productId);

        Database::update('products', [
            'slug'          => $slug,
            'sku'           => $request->text('sku'),
            'category_id'   => $request->int('category_id') ?: null,
            'price'         => $this->decimal($request->text('price')),
            'compare_price' => $this->nullableDecimal($request->text('compare_price')),
            'status'        => in_array($request->text('status'), ['draft', 'published', 'archived'], true)
                ? $request->text('status') : 'draft',
            'is_featured'   => $request->text('is_featured') === '1' ? 1 : 0,
            'size_mode'     => $request->text('size_mode') === 'bra_brief' ? 'bra_brief' : 'single',
            'position'      => $request->int('position'),
        ], '`id` = :id', ['id' => $productId]);

        foreach (array_keys($this->langs) as $code) {
            Product::saveTranslation($productId, $code, [
                'name'              => $request->text("t_{$code}_name"),
                'tagline'           => $request->text("t_{$code}_tagline"),
                'short_description' => $request->text("t_{$code}_short_description"),
                'description'       => safe_html((string) $request->input("t_{$code}_description", '')),
                'composition'       => safe_html((string) $request->input("t_{$code}_composition", '')),
                'care'              => safe_html((string) $request->input("t_{$code}_care", '')),
                'shipping_returns'  => safe_html((string) $request->input("t_{$code}_shipping_returns", '')),
                'meta_title'        => $request->text("t_{$code}_meta_title"),
                'meta_description'  => $request->text("t_{$code}_meta_description"),
            ]);
        }

        Auth::log('update', 'product', $productId, $request->text('slug'));
        $this->saved();
        $this->redirect(admin_url('products/' . $productId));
    }

    public function deleteProduct(Request $request): void
    {
        $this->boot($request);
        $this->requireCsrf($request);
        $this->requireAdmin();

        $productId = (int) $request->param('id');

        $sold = (int) Database::value(
            'SELECT COUNT(*) FROM `order_items` WHERE `product_id` = :id',
            ['id' => $productId],
            0
        );

        // Con pedidos asociados se archiva, para no romper el historial.
        if ($sold > 0) {
            Database::update('products', ['status' => 'archived'], '`id` = :id', ['id' => $productId]);
            Auth::log('archive', 'product', $productId);
            Flash::info('Este producto tiene pedidos, así que se ha archivado en lugar de borrarse.');
            $this->redirect(admin_url('products'));
        }

        Database::delete('products', '`id` = :id', ['id' => $productId]);
        Auth::log('delete', 'product', $productId);
        $this->saved('Producto eliminado.');
        $this->redirect(admin_url('products'));
    }

    // ------------------------------------------------------------------
    // Imágenes
    // ------------------------------------------------------------------

    public function uploadImage(Request $request): void
    {
        $this->boot($request);
        $this->requireCsrf($request);

        $productId = (int) $request->param('id');
        $file      = $_FILES['image'] ?? null;

        if (!is_array($file)) {
            Flash::error('Elige una imagen antes de subirla.');
            $this->redirect(admin_url('products/' . $productId));
        }

        $alt    = $request->text('alt');
        $result = Uploader::image($file, $alt);

        if (!$result['ok']) {
            Flash::error($result['message']);
            $this->redirect(admin_url('products/' . $productId));
        }

        $isFirst = (int) Database::value(
            'SELECT COUNT(*) FROM `product_images` WHERE `product_id` = :id',
            ['id' => $productId],
            0
        ) === 0;

        Database::insert('product_images', [
            'product_id' => $productId,
            'path'       => $result['path'],
            'alt'        => $alt,
            'kind'       => in_array($request->text('kind'), ['model', 'product', 'detail', 'packaging'], true)
                ? $request->text('kind') : 'product',
            'position'   => (int) Database::value(
                'SELECT COALESCE(MAX(`position`),0)+1 FROM `product_images` WHERE `product_id` = :id',
                ['id' => $productId],
                1
            ),
            'is_primary' => $isFirst ? 1 : 0,
        ]);

        $this->saved('Imagen añadida.');
        $this->redirect(admin_url('products/' . $productId));
    }

    public function deleteImage(Request $request): void
    {
        $this->boot($request);
        $this->requireCsrf($request);

        $productId = (int) $request->param('id');

        Database::delete('product_images', '`id` = :i AND `product_id` = :p', [
            'i' => (int) $request->param('imageId'),
            'p' => $productId,
        ]);

        $this->saved('Imagen eliminada.');
        $this->redirect(admin_url('products/' . $productId));
    }

    public function makePrimary(Request $request): void
    {
        $this->boot($request);
        $this->requireCsrf($request);

        $productId = (int) $request->param('id');

        Database::update('product_images', ['is_primary' => 0], '`product_id` = :p', ['p' => $productId]);
        Database::update('product_images', ['is_primary' => 1], '`id` = :i AND `product_id` = :p', [
            'i' => (int) $request->param('imageId'),
            'p' => $productId,
        ]);

        $this->saved('Imagen principal actualizada.');
        $this->redirect(admin_url('products/' . $productId));
    }

    // ------------------------------------------------------------------
    // Variantes y stock
    // ------------------------------------------------------------------

    public function saveVariants(Request $request): void
    {
        $this->boot($request);
        $this->requireCsrf($request);

        $productId = (int) $request->param('id');
        $rows      = $request->input('variant', []);

        if (is_array($rows)) {
            foreach ($rows as $variantId => $row) {
                Database::update('product_variants', [
                    'sku'            => mb_substr((string) ($row['sku'] ?? ''), 0, 60),
                    'size'           => ($row['size'] ?? '') === '' ? null : mb_substr((string) $row['size'], 0, 16),
                    'size_bra'       => ($row['size_bra'] ?? '') === '' ? null : mb_substr((string) $row['size_bra'], 0, 16),
                    'size_brief'     => ($row['size_brief'] ?? '') === '' ? null : mb_substr((string) $row['size_brief'], 0, 16),
                    'stock'          => max(0, (int) ($row['stock'] ?? 0)),
                    'price_override' => $this->nullableDecimal((string) ($row['price_override'] ?? '')),
                    'is_active'      => isset($row['is_active']) ? 1 : 0,
                    'position'       => (int) ($row['position'] ?? 0),
                ], '`id` = :i AND `product_id` = :p', ['i' => (int) $variantId, 'p' => $productId]);
            }
        }

        Auth::log('update', 'variants', $productId);
        $this->saved('Tallas y stock actualizados.');
        $this->redirect(admin_url('products/' . $productId));
    }

    public function addVariant(Request $request): void
    {
        $this->boot($request);
        $this->requireCsrf($request);

        $productId = (int) $request->param('id');
        $product   = Product::find($productId);

        if ($product === null) {
            $this->redirect(admin_url('products'));
        }

        $isPair = $product['size_mode'] === 'bra_brief';
        $size   = $request->text('size');
        $bra    = $request->text('size_bra');
        $brief  = $request->text('size_brief');

        if (($isPair && ($bra === '' || $brief === '')) || (!$isPair && $size === '')) {
            Flash::error('Indica la talla antes de añadirla.');
            $this->redirect(admin_url('products/' . $productId));
        }

        $duplicate = $isPair
            ? Database::value(
                'SELECT `id` FROM `product_variants` WHERE `product_id` = :p AND `size_bra` = :a AND `size_brief` = :b',
                ['p' => $productId, 'a' => $bra, 'b' => $brief]
            )
            : Database::value(
                'SELECT `id` FROM `product_variants` WHERE `product_id` = :p AND `size` = :s',
                ['p' => $productId, 's' => $size]
            );

        if ($duplicate) {
            Flash::error('Esa talla ya está en la lista.');
            $this->redirect(admin_url('products/' . $productId));
        }

        Database::insert('product_variants', [
            'product_id' => $productId,
            'sku'        => $request->text('sku'),
            'size'       => $isPair ? null : $size,
            'size_bra'   => $isPair ? $bra : null,
            'size_brief' => $isPair ? $brief : null,
            'stock'      => max(0, $request->int('stock')),
            'position'   => (int) Database::value(
                'SELECT COALESCE(MAX(`position`),0)+1 FROM `product_variants` WHERE `product_id` = :p',
                ['p' => $productId],
                1
            ),
        ]);

        $this->saved('Talla añadida.');
        $this->redirect(admin_url('products/' . $productId));
    }

    public function deleteVariant(Request $request): void
    {
        $this->boot($request);
        $this->requireCsrf($request);

        $productId = (int) $request->param('id');
        $variantId = (int) $request->param('variantId');

        $sold = (int) Database::value(
            'SELECT COUNT(*) FROM `order_items` WHERE `variant_id` = :id',
            ['id' => $variantId],
            0
        );

        if ($sold > 0) {
            Database::update('product_variants', ['is_active' => 0, 'stock' => 0],
                '`id` = :i AND `product_id` = :p', ['i' => $variantId, 'p' => $productId]);
            Flash::info('Esa talla se ha vendido alguna vez, así que se ha desactivado en lugar de borrarse.');
            $this->redirect(admin_url('products/' . $productId));
        }

        Database::delete('product_variants', '`id` = :i AND `product_id` = :p', ['i' => $variantId, 'p' => $productId]);
        $this->saved('Talla eliminada.');
        $this->redirect(admin_url('products/' . $productId));
    }

    public function stock(Request $request): void
    {
        $this->boot($request);

        $rows = Database::all(
            'SELECT v.*, p.`slug`, p.`size_mode`, pt.`name` AS product_name
               FROM `product_variants` v
               JOIN `products` p ON p.`id` = v.`product_id`
               LEFT JOIN `product_translations` pt ON pt.`product_id` = p.`id` AND pt.`lang` = :lang
              ORDER BY p.`position`, v.`position`',
            ['lang' => Lang::default()]
        );

        $grouped = [];

        foreach ($rows as $row) {
            $grouped[(string) $row['product_name']][] = $row;
        }

        $this->view('admin/stock', [
            'grouped'   => $grouped,
            'threshold' => Settings::int('low_stock_threshold', 5),
        ]);
    }

    public function saveStock(Request $request): void
    {
        $this->boot($request);
        $this->requireCsrf($request);

        $rows = $request->input('stock', []);

        if (is_array($rows)) {
            foreach ($rows as $variantId => $value) {
                Database::update('product_variants', ['stock' => max(0, (int) $value)],
                    '`id` = :id', ['id' => (int) $variantId]);
            }
        }

        Auth::log('update', 'stock', null, 'Actualización de existencias');
        $this->saved('Existencias actualizadas.');
        $this->redirect(admin_url('stock'));
    }

    // ------------------------------------------------------------------
    // Categorías
    // ------------------------------------------------------------------

    public function categories(Request $request): void
    {
        $this->boot($request);

        $categories = Category::all(Lang::default());

        foreach ($categories as $index => $category) {
            $categories[$index]['translations'] = Category::translations((int) $category['id']);
        }

        $this->view('admin/categories', ['categories' => $categories]);
    }

    public function saveCategories(Request $request): void
    {
        $this->boot($request);
        $this->requireCsrf($request);

        $rows = $request->input('category', []);

        if (is_array($rows)) {
            foreach ($rows as $categoryId => $row) {
                $categoryId = (int) $categoryId;

                Database::update('categories', [
                    'image'     => mb_substr((string) ($row['image'] ?? ''), 0, 255),
                    'position'  => (int) ($row['position'] ?? 0),
                    'is_active' => isset($row['is_active']) ? 1 : 0,
                ], '`id` = :id', ['id' => $categoryId]);

                foreach (array_keys($this->langs) as $code) {
                    Category::saveTranslation($categoryId, $code, [
                        'name'             => mb_substr((string) ($row['name'][$code] ?? ''), 0, 160),
                        'description'      => (string) ($row['description'][$code] ?? ''),
                        'meta_title'       => mb_substr((string) ($row['meta_title'][$code] ?? ''), 0, 190),
                        'meta_description' => mb_substr((string) ($row['meta_description'][$code] ?? ''), 0, 255),
                    ]);
                }
            }
        }

        Auth::log('update', 'categories');
        $this->saved();
        $this->redirect(admin_url('categories'));
    }

    /**
     * Genera un slug libre, añadiendo sufijo si ya existe.
     */
    private function uniqueSlug(string $source, int $exceptId = 0): string
    {
        $base = slugify($source);
        $slug = $base;
        $n    = 2;

        while (Database::value(
            'SELECT `id` FROM `products` WHERE `slug` = :s AND `id` <> :id',
            ['s' => $slug, 'id' => $exceptId]
        )) {
            $slug = $base . '-' . $n++;
        }

        return $slug;
    }
}
