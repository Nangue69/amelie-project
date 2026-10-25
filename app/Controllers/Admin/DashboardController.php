<?php

declare(strict_types=1);

namespace App\Controllers\Admin;

use App\Core\Request;
use App\Core\Settings;
use App\Models\Message;
use App\Models\Order;
use App\Models\Product;
use App\Models\Subscriber;
use App\Models\User;

final class DashboardController extends AdminController
{
    public function index(Request $request): void
    {
        $this->boot($request);

        $this->view('admin/dashboard', [
            'stats' => [
                'revenueMonth' => Order::revenueThisMonth(),
                'revenueTotal' => Order::totalRevenue(),
                'ordersTotal'  => Order::countAll(),
                'ordersPending'=> Order::countByStatus('pending'),
                'products'     => Product::countByStatus('published'),
                'drafts'       => Product::countByStatus('draft'),
                'subscribers'  => Subscriber::countActive(),
                'messages'     => Message::countUnread(),
            ],
            'recentOrders' => Order::recent(6),
            'lowStock'     => Product::lowStock(Settings::int('low_stock_threshold', 5)),
            'activity'     => User::activityLog(8),
            'comingSoon'   => Settings::bool('coming_soon'),
        ]);
    }
}
