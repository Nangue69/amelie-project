<?php

declare(strict_types=1);

namespace App\Controllers\Site;

use App\Core\Cart;
use App\Core\Flash;
use App\Core\Request;
use App\Core\Seo;
use App\Core\Settings;
use App\Models\Product;

final class CartController extends SiteController
{
    public function index(Request $request): void
    {
        $this->boot($request);
        $this->guardComingSoon($request);

        Seo::set([
            'title'   => t('cart.title'),
            'noindex' => true,
            'path'    => 'cart',
        ]);

        $totals = Cart::totals();

        $this->view('site/cart', [
            'totals'        => $totals,
            'freeFrom'      => Settings::float('free_shipping_from', 0),
        ]);
    }

    public function add(Request $request): void
    {
        $this->boot($request);
        $this->requireCsrf($request);

        $variantId = $request->int('variant_id');
        $quantity  = max(1, $request->int('quantity', 1));

        if ($variantId < 1 || Product::variant($variantId) === null) {
            $this->respond($request, false, t('msg.select_size_first'));
        }

        if (!Cart::add($variantId, $quantity)) {
            $this->respond($request, false, t('msg.stock_limit'));
        }

        $this->respond($request, true, t('msg.added'));
    }

    public function update(Request $request): void
    {
        $this->boot($request);
        $this->requireCsrf($request);

        Cart::update($request->int('variant_id'), $request->int('quantity'));

        $this->respond($request, true, t('msg.updated'));
    }

    public function remove(Request $request): void
    {
        $this->boot($request);
        $this->requireCsrf($request);

        Cart::remove($request->int('variant_id'));

        $this->respond($request, true, t('msg.removed'));
    }

    public function coupon(Request $request): void
    {
        $this->boot($request);
        $this->requireCsrf($request);

        if ($request->text('action') === 'remove') {
            Cart::removeCoupon();
            Flash::info(t('msg.updated'));
            $this->redirect(lang_url('cart'));
        }

        $result = Cart::applyCoupon($request->text('code'));

        $result['ok'] ? Flash::success($result['message']) : Flash::error($result['message']);

        $this->redirect(lang_url('cart'));
    }

    /**
     * Responde en JSON a las peticiones del carrito lateral y con redireccion
     * a los envios de formulario sin JavaScript.
     */
    private function respond(Request $request, bool $ok, string $message): never
    {
        if ($request->isAjax()) {
            $totals = Cart::totals();

            $this->json([
                'ok'      => $ok,
                'message' => $message,
                'count'   => Cart::count(),
                'items'   => array_map(
                    static fn (array $item): array => [
                        'variant_id' => $item['variant_id'],
                        'name'       => $item['name'],
                        'size_label' => $item['size_label'],
                        'image'      => img($item['image']),
                        'url'        => lang_url('product/' . $item['slug']),
                        'quantity'   => $item['quantity'],
                        'price'      => money($item['unit_price']),
                        'line_total' => money($item['line_total']),
                    ],
                    $totals['items']
                ),
                'subtotal' => money($totals['subtotal']),
            ]);
        }

        $ok ? Flash::success($message) : Flash::error($message);
        $this->back();
    }
}
