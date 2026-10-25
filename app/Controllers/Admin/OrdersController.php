<?php

declare(strict_types=1);

namespace App\Controllers\Admin;

use App\Core\Auth;
use App\Core\Database;
use App\Core\Flash;
use App\Core\Mailer;
use App\Core\Request;
use App\Models\Order;

final class OrdersController extends AdminController
{
    public function index(Request $request): void
    {
        $this->boot($request);

        $status = $request->text('status');
        $search = $request->text('q');

        $this->view('admin/orders', [
            'orders' => Order::all($status, $search),
            'status' => $status,
            'search' => $search,
            'counts' => $this->counts(),
        ]);
    }

    public function show(Request $request): void
    {
        $this->boot($request);

        $order = Order::find((int) $request->param('id'));

        if ($order === null) {
            Flash::error('Ese pedido no existe.');
            $this->redirect(admin_url('orders'));
        }

        $this->view('admin/order-detail', [
            'order' => $order,
            'items' => Order::items((int) $order['id']),
        ]);
    }

    public function update(Request $request): void
    {
        $this->boot($request);
        $this->requireCsrf($request);

        $orderId = (int) $request->param('id');
        $order   = Order::find($orderId);

        if ($order === null) {
            $this->redirect(admin_url('orders'));
        }

        $status = $request->text('status');
        $before = (string) $order['status'];

        Order::updateStatus($orderId, $status);

        Database::update('orders', [
            'tracking_code' => mb_substr($request->text('tracking_code'), 0, 120),
            'admin_notes'   => mb_substr($request->text('admin_notes'), 0, 2000),
        ], '`id` = :id', ['id' => $orderId]);

        // Avisar a la clienta sólo cuando el pedido sale hacia ella.
        if ($status === 'shipped' && $before !== 'shipped') {
            $this->notifyShipped($order, $request->text('tracking_code'));
        }

        Auth::log('update', 'order', $orderId, 'Estado: ' . $status);
        $this->saved('Pedido actualizado.');
        $this->redirect(admin_url('orders/' . $orderId));
    }

    private function counts(): array
    {
        $counts = [];

        foreach (Order::STATUSES as $status) {
            $counts[$status] = Order::countByStatus($status);
        }

        return $counts;
    }

    private function notifyShipped(array $order, string $tracking): void
    {
        $body = '<p>Hola ' . e((string) $order['customer_name']) . ',</p>'
            . '<p>Tu pedido <strong>' . e((string) $order['order_number']) . '</strong> ya está en camino.</p>';

        if ($tracking !== '') {
            $body .= '<p>Número de seguimiento: <strong>' . e($tracking) . '</strong></p>';
        }

        Mailer::send(
            (string) $order['customer_email'],
            'Tu pedido ' . $order['order_number'] . ' está en camino',
            $body
        );
    }
}
