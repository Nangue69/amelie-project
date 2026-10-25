<?php

declare(strict_types=1);

namespace App\Controllers\Admin;

use App\Core\Auth;
use App\Core\Controller;
use App\Core\Flash;
use App\Core\Lang;
use App\Core\Request;
use App\Core\Settings;
use App\Core\View;
use App\Models\Message;
use App\Models\Order;

abstract class AdminController extends Controller
{
    /** Idiomas disponibles para las pestañas de traducción. */
    protected array $langs = [];

    /**
     * Toda ruta del panel pasa por aquí. Sin sesión, al login.
     */
    protected function boot(Request $request): void
    {
        if (!Auth::check()) {
            $this->redirect(admin_url('login'));
        }

        $this->langs = Lang::available();

        View::share('user', Auth::user());
        View::share('langs', $this->langs);
        View::share('currentPath', $request->path());
        View::share('siteName', Settings::get('site_name', 'AMÉLIE'));
        View::share('defaultLang', Lang::default());
        View::share('badges', [
            'orders'   => Order::countByStatus('pending'),
            'messages' => Message::countUnread(),
        ]);
    }

    protected function view(string $template, array $data = [], string $layout = 'admin'): void
    {
        echo View::render($template, $data, $layout);
    }

    /**
     * Acciones destructivas o de configuración global: sólo el rol admin.
     */
    protected function requireAdmin(): void
    {
        if (Auth::isAdmin()) {
            return;
        }

        Flash::error('Esta acción sólo está disponible para administradores.');
        $this->redirect(admin_url());
    }

    protected function saved(string $message = 'Cambios guardados.'): void
    {
        Flash::success($message);
    }

    /**
     * Normaliza un campo de precio escrito con coma o con punto.
     */
    protected function decimal(string $value, float $default = 0.0): float
    {
        $value = trim(str_replace([' ', '€'], '', $value));
        $value = str_replace(',', '.', $value);

        return is_numeric($value) ? round((float) $value, 2) : $default;
    }

    protected function nullableDecimal(string $value): ?float
    {
        return trim($value) === '' ? null : $this->decimal($value);
    }

    protected function nullableDate(string $value): ?string
    {
        $value = trim($value);

        return preg_match('/^\d{4}-\d{2}-\d{2}$/', $value) === 1 ? $value : null;
    }
}
