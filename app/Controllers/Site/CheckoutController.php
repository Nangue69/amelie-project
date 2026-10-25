<?php

declare(strict_types=1);

namespace App\Controllers\Site;

use App\Core\Cart;
use App\Core\Flash;
use App\Core\Mailer;
use App\Core\Request;
use App\Core\Seo;
use App\Core\Session;
use App\Core\Settings;
use App\Core\Validator;
use App\Models\Order;
use App\Models\Shipping;

final class CheckoutController extends SiteController
{
    public function index(Request $request): void
    {
        $this->boot($request);
        $this->guardComingSoon($request);

        if (Cart::isEmpty()) {
            $this->redirect(lang_url('cart'));
        }

        Seo::set(['title' => t('checkout.title'), 'noindex' => true, 'path' => 'checkout']);

        $country = $request->text('country', Flash::old('country', $this->defaultCountry()));

        $this->view('site/checkout', [
            'totals'    => Cart::totals($country),
            'countries' => Shipping::countries(),
            'country'   => $country,
            'errors'    => Flash::errors(),
            'methods'   => $this->paymentMethods(),
        ]);
    }

    public function store(Request $request): void
    {
        $this->boot($request);
        $this->requireCsrf($request);

        if (Cart::isEmpty()) {
            $this->redirect(lang_url('cart'));
        }

        $methods   = array_keys($this->paymentMethods());
        $validator = new Validator($request->all());

        $validator->required('name', t('msg.fields_required'))
            ->required('email', t('msg.fields_required'))
            ->email('email', t('msg.invalid_email'))
            ->required('address', t('msg.fields_required'))
            ->required('city', t('msg.fields_required'))
            ->required('postal', t('msg.fields_required'))
            ->required('country', t('msg.fields_required'))
            ->in('country', array_keys(Shipping::countries()), t('msg.fields_required'))
            ->in('payment_method', $methods, t('msg.fields_required'))
            ->accepted('terms', t('msg.fields_required'));

        if ($methods === []) {
            Flash::error(t('msg.error'));
            $this->redirect(lang_url('checkout'));
        }

        if ($validator->fails()) {
            Flash::error($validator->firstError());
            Flash::setErrors($validator->errors());
            Flash::keepInput($request->all());
            $this->redirect(lang_url('checkout'));
        }

        $country = strtoupper($validator->value('country'));
        $totals  = Cart::totals($country);

        if ($totals['items'] === []) {
            Flash::error(t('msg.stock_limit'));
            $this->redirect(lang_url('cart'));
        }

        $paymentMethod = $validator->value('payment_method');

        $orderId = Order::create([
            'order_number'   => Order::nextNumber(),
            'lang'           => $this->lang,
            'status'         => 'pending',
            'payment_method' => $paymentMethod,
            'payment_status' => 'pending',
            'customer_name'  => $validator->value('name'),
            'customer_email' => $validator->value('email'),
            'customer_phone' => $validator->value('phone'),
            'ship_address'   => $validator->value('address'),
            'ship_address2'  => $validator->value('address2'),
            'ship_city'      => $validator->value('city'),
            'ship_postal'    => $validator->value('postal'),
            'ship_province'  => $validator->value('province'),
            'ship_country'   => $country,
            'subtotal'       => $totals['subtotal'],
            'shipping_cost'  => $totals['shipping'],
            'discount_total' => $totals['discount'],
            'total'          => $totals['total'],
            'currency'       => Settings::get('currency', 'EUR'),
            'coupon_code'    => $totals['coupon']['code'] ?? '',
            'notes'          => mb_substr($validator->value('notes'), 0, 2000),
        ], $totals['items']);

        $order = Order::find($orderId);

        if ($order === null) {
            Flash::error(t('msg.error'));
            $this->redirect(lang_url('checkout'));
        }

        Cart::clear();
        Flash::clearInput();

        $this->sendConfirmation($order, $totals['items']);

        // El numero se guarda en sesion para que la pagina de gracias sea
        // visible solo por quien acaba de comprar.
        Session::set('_last_order', $order['order_number']);

        $this->redirect(lang_url('checkout/success/' . $order['order_number']));
    }

    public function success(Request $request): void
    {
        $this->boot($request);

        $number = $request->param('number');

        if (Session::get('_last_order') !== $number) {
            $this->renderNotFound();

            return;
        }

        $order = Order::findByNumber($number);

        if ($order === null) {
            $this->renderNotFound();

            return;
        }

        Seo::set(['title' => t('checkout.success_title'), 'noindex' => true, 'path' => 'checkout']);

        $this->view('site/checkout-success', [
            'order' => $order,
            'items' => Order::items((int) $order['id']),
        ]);
    }

    /**
     * Metodos de pago activados desde el panel.
     */
    private function paymentMethods(): array
    {
        $methods = [];

        if (Settings::bool('payment_bank_transfer', true)) {
            $methods['bank_transfer'] = [
                'label'       => t('checkout.bank'),
                'description' => t('checkout.bank_desc'),
            ];
        }

        if (Settings::bool('payment_stripe') && Settings::get('stripe_secret_key') !== '') {
            $methods['card'] = [
                'label'       => t('checkout.card'),
                'description' => t('checkout.card_desc'),
            ];
        }

        return $methods;
    }

    private function defaultCountry(): string
    {
        return match ($this->lang) {
            'nl'    => 'NL',
            'it'    => 'IT',
            'en'    => 'ES',
            default => 'ES',
        };
    }

    private function sendConfirmation(array $order, array $items): void
    {
        $rows = '';

        foreach ($items as $item) {
            $rows .= '<tr>'
                . '<td style="padding:8px 0;">' . e($item['name']) . '<br><small>' . e($item['size_label']) . '</small></td>'
                . '<td style="padding:8px 0;text-align:center;">' . (int) $item['quantity'] . '</td>'
                . '<td style="padding:8px 0;text-align:right;">' . money($item['line_total']) . '</td>'
                . '</tr>';
        }

        $bank = '';

        if ($order['payment_method'] === 'bank_transfer') {
            $bank = '<p>' . e(t('checkout.bank_instructions')) . '</p>'
                . '<p><strong>' . e(Settings::get('bank_holder')) . '</strong><br>'
                . e(Settings::get('bank_iban')) . '<br>'
                . e(t('checkout.success_number')) . ': <strong>' . e($order['order_number']) . '</strong></p>';
        }

        $body = '<p>' . e(t('checkout.success_text')) . '</p>'
            . '<p>' . e(t('checkout.success_number')) . ': <strong>' . e($order['order_number']) . '</strong></p>'
            . '<table style="width:100%;border-collapse:collapse;">' . $rows . '</table>'
            . '<p style="text-align:right;">'
            . e(t('cart.subtotal')) . ': ' . money($order['subtotal']) . '<br>'
            . e(t('cart.shipping')) . ': ' . money($order['shipping_cost']) . '<br>'
            . '<strong>' . e(t('cart.total')) . ': ' . money($order['total']) . '</strong></p>'
            . $bank;

        Mailer::send($order['customer_email'], t('checkout.success_title') . ' · ' . $order['order_number'], $body);

        Mailer::toAdmin(
            'Nuevo pedido ' . $order['order_number'],
            '<p>' . e($order['customer_name']) . ' — ' . money($order['total']) . '</p>' . $body
        );
    }
}
