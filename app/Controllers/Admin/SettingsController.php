<?php

declare(strict_types=1);

namespace App\Controllers\Admin;

use App\Core\Auth;
use App\Core\Database;
use App\Core\Flash;
use App\Core\Lang;
use App\Core\Request;
use App\Core\Settings;
use App\Models\Coupon;
use App\Models\Shipping;
use App\Models\Theme;
use App\Models\User;

final class SettingsController extends AdminController
{
    /** Claves editables, agrupadas tal y como se muestran en el panel. */
    private const EDITABLE = [
        'general' => ['site_name', 'site_tagline', 'default_lang', 'coming_soon', 'low_stock_threshold', 'products_per_page'],
        'contact' => ['contact_email', 'contact_phone', 'contact_address', 'company_legal_name', 'company_vat'],
        'shop'    => ['currency', 'currency_symbol', 'free_shipping_from'],
        'social'  => ['instagram_url', 'facebook_url', 'tiktok_url', 'pinterest_url'],
        'seo'     => ['meta_title_suffix', 'og_image', 'analytics_id', 'robots_index'],
        'payment' => ['payment_bank_transfer', 'bank_holder', 'bank_name', 'bank_iban', 'bank_swift',
                      'payment_stripe', 'stripe_public_key', 'stripe_secret_key'],
        'privacy' => ['cookie_banner'],
        'home'    => ['home_hero_image', 'home_story_image', 'home_craft_image', 'home_manifesto_image'],
    ];

    /** Claves que se guardan como interruptor 0/1. */
    private const TOGGLES = [
        'coming_soon', 'robots_index', 'payment_bank_transfer', 'payment_stripe', 'cookie_banner',
    ];

    public function index(Request $request): void
    {
        $this->boot($request);

        $this->view('admin/settings', [
            'settings' => Settings::all(),
            'groups'   => self::EDITABLE,
            'toggles'  => self::TOGGLES,
            'media'    => Database::all('SELECT `path`, `filename` FROM `media` ORDER BY `created_at` DESC LIMIT 60'),
        ]);
    }

    public function save(Request $request): void
    {
        $this->boot($request);
        $this->requireCsrf($request);

        foreach (self::EDITABLE as $keys) {
            foreach ($keys as $key) {
                if (in_array($key, self::TOGGLES, true)) {
                    Settings::put($key, $request->text($key) === '1' ? '1' : '0');

                    continue;
                }

                if ($key === 'default_lang') {
                    $value = $request->text($key);
                    Settings::put($key, Lang::isValid($value) ? $value : Lang::default());

                    continue;
                }

                Settings::put($key, $request->text($key));
            }
        }

        Auth::log('update', 'settings', null, 'Ajustes generales');
        $this->saved();
        $this->redirect(admin_url('settings'));
    }

    // ------------------------------------------------------------------
    // Temas
    // ------------------------------------------------------------------

    public function themes(Request $request): void
    {
        $this->boot($request);

        $this->view('admin/themes', [
            'themes' => Theme::all(),
            'active' => Theme::activeKey(),
        ]);
    }

    public function activateTheme(Request $request): void
    {
        $this->boot($request);
        $this->requireCsrf($request);

        $key = $request->text('theme');

        if (!Theme::activate($key)) {
            Flash::error('Ese tema no existe.');
            $this->redirect(admin_url('themes'));
        }

        Auth::log('update', 'theme', null, $key);
        $this->saved('Tema aplicado. Ya se ve en la web.');
        $this->redirect(admin_url('themes'));
    }

    // ------------------------------------------------------------------
    // Envíos
    // ------------------------------------------------------------------

    public function shipping(Request $request): void
    {
        $this->boot($request);

        $this->view('admin/shipping', ['rates' => Shipping::all()]);
    }

    public function saveShipping(Request $request): void
    {
        $this->boot($request);
        $this->requireCsrf($request);

        $rows = $request->input('rate', []);

        if (is_array($rows)) {
            foreach ($rows as $rateId => $row) {
                Database::update('shipping_rates', [
                    'zone_name'     => mb_substr((string) ($row['zone_name'] ?? ''), 0, 120),
                    'countries'     => strtoupper(preg_replace('/\s+/', '', (string) ($row['countries'] ?? '')) ?? ''),
                    'price'         => $this->decimal((string) ($row['price'] ?? '0')),
                    'free_from'     => $this->nullableDecimal((string) ($row['free_from'] ?? '')),
                    'delivery_days' => mb_substr((string) ($row['delivery_days'] ?? ''), 0, 40),
                    'is_active'     => isset($row['is_active']) ? 1 : 0,
                    'position'      => (int) ($row['position'] ?? 0),
                ], '`id` = :id', ['id' => (int) $rateId]);
            }
        }

        Auth::log('update', 'shipping');
        $this->saved();
        $this->redirect(admin_url('shipping'));
    }

    // ------------------------------------------------------------------
    // Cupones
    // ------------------------------------------------------------------

    public function coupons(Request $request): void
    {
        $this->boot($request);

        $this->view('admin/coupons', ['coupons' => Coupon::all()]);
    }

    public function saveCoupons(Request $request): void
    {
        $this->boot($request);
        $this->requireCsrf($request);

        $rows = $request->input('coupon', []);

        if (is_array($rows)) {
            foreach ($rows as $couponId => $row) {
                Database::update('coupons', [
                    'type'        => in_array($row['type'] ?? '', ['percent', 'fixed', 'free_shipping'], true)
                        ? $row['type'] : 'percent',
                    'value'       => $this->decimal((string) ($row['value'] ?? '0')),
                    'min_amount'  => $this->decimal((string) ($row['min_amount'] ?? '0')),
                    'starts_at'   => $this->nullableDate((string) ($row['starts_at'] ?? '')),
                    'ends_at'     => $this->nullableDate((string) ($row['ends_at'] ?? '')),
                    'usage_limit' => ($row['usage_limit'] ?? '') === '' ? null : max(1, (int) $row['usage_limit']),
                    'is_active'   => isset($row['is_active']) ? 1 : 0,
                ], '`id` = :id', ['id' => (int) $couponId]);
            }
        }

        Auth::log('update', 'coupons');
        $this->saved();
        $this->redirect(admin_url('coupons'));
    }

    public function addCoupon(Request $request): void
    {
        $this->boot($request);
        $this->requireCsrf($request);

        $code = strtoupper(preg_replace('/[^A-Za-z0-9_-]/', '', $request->text('code')) ?? '');

        if ($code === '') {
            Flash::error('Escribe un código: sólo letras, números y guiones.');
            $this->redirect(admin_url('coupons'));
        }

        if (Database::value('SELECT `id` FROM `coupons` WHERE `code` = :c', ['c' => $code])) {
            Flash::error('Ese código ya existe.');
            $this->redirect(admin_url('coupons'));
        }

        Database::insert('coupons', [
            'code'       => $code,
            'type'       => in_array($request->text('type'), ['percent', 'fixed', 'free_shipping'], true)
                ? $request->text('type') : 'percent',
            'value'      => $this->decimal($request->text('value')),
            'min_amount' => $this->decimal($request->text('min_amount')),
            'is_active'  => 1,
        ]);

        Auth::log('create', 'coupon', null, $code);
        $this->saved('Cupón creado.');
        $this->redirect(admin_url('coupons'));
    }

    public function deleteCoupon(Request $request): void
    {
        $this->boot($request);
        $this->requireCsrf($request);

        Database::delete('coupons', '`id` = :id', ['id' => (int) $request->param('id')]);
        $this->saved('Cupón eliminado.');
        $this->redirect(admin_url('coupons'));
    }

    // ------------------------------------------------------------------
    // Idiomas
    // ------------------------------------------------------------------

    public function languages(Request $request): void
    {
        $this->boot($request);

        $this->view('admin/languages', [
            'languages' => Database::all('SELECT * FROM `languages` ORDER BY `position`'),
            'counts'    => $this->stringCounts(),
        ]);
    }

    public function saveLanguages(Request $request): void
    {
        $this->boot($request);
        $this->requireCsrf($request);
        $this->requireAdmin();

        $rows    = $request->input('language', []);
        $default = $request->text('default_lang');
        $active  = [];

        if (is_array($rows)) {
            foreach ($rows as $code => $row) {
                $code = substr((string) $code, 0, 2);

                $isActive = isset($row['is_active']) || $code === $default;

                Database::update('languages', [
                    'native_name' => mb_substr((string) ($row['native_name'] ?? $code), 0, 50),
                    'is_active'   => $isActive ? 1 : 0,
                    'is_default'  => $code === $default ? 1 : 0,
                    'position'    => (int) ($row['position'] ?? 0),
                ], '`code` = :c', ['c' => $code]);

                if ($isActive) {
                    $active[] = $code;
                }
            }
        }

        if (in_array($default, $active, true)) {
            Settings::put('default_lang', $default);
        }

        Auth::log('update', 'languages');
        $this->saved('Idiomas actualizados.');
        $this->redirect(admin_url('languages'));
    }

    private function stringCounts(): array
    {
        $rows   = Database::all('SELECT `lang`, COUNT(*) AS total FROM `ui_strings` GROUP BY `lang`');
        $counts = [];

        foreach ($rows as $row) {
            $counts[$row['lang']] = (int) $row['total'];
        }

        return $counts;
    }

    // ------------------------------------------------------------------
    // Usuarios y actividad
    // ------------------------------------------------------------------

    public function users(Request $request): void
    {
        $this->boot($request);
        $this->requireAdmin();

        $this->view('admin/users', ['users' => User::all()]);
    }

    public function saveUsers(Request $request): void
    {
        $this->boot($request);
        $this->requireCsrf($request);
        $this->requireAdmin();

        $rows = $request->input('user', []);

        if (is_array($rows)) {
            foreach ($rows as $userId => $row) {
                $userId = (int) $userId;
                $data   = [
                    'name' => mb_substr((string) ($row['name'] ?? ''), 0, 120),
                    'role' => ($row['role'] ?? 'editor') === 'admin' ? 'admin' : 'editor',
                ];

                // No permitir quedarse sin ningun administrador activo.
                $wouldBeActive = isset($row['is_active']);

                if (!$wouldBeActive && $userId === Auth::id()) {
                    $wouldBeActive = true;
                }

                $data['is_active'] = $wouldBeActive ? 1 : 0;

                User::update($userId, $data);

                $password = (string) ($row['password'] ?? '');

                if ($password !== '') {
                    if (mb_strlen($password) < 8) {
                        Flash::error('La contraseña debe tener al menos 8 caracteres.');

                        continue;
                    }

                    User::setPassword($userId, $password);
                }
            }
        }

        if (User::countAdmins() === 0) {
            User::update(Auth::id(), ['role' => 'admin', 'is_active' => 1]);
            Flash::info('Se ha mantenido tu cuenta como administradora: la tienda necesita al menos una.');
        }

        Auth::log('update', 'users');
        $this->saved();
        $this->redirect(admin_url('users'));
    }

    public function addUser(Request $request): void
    {
        $this->boot($request);
        $this->requireCsrf($request);
        $this->requireAdmin();

        $name     = $request->text('name');
        $email    = mb_strtolower($request->text('email'));
        $password = (string) $request->input('password', '');

        if ($name === '' || filter_var($email, FILTER_VALIDATE_EMAIL) === false || mb_strlen($password) < 8) {
            Flash::error('Revisa el nombre, el correo y una contraseña de al menos 8 caracteres.');
            $this->redirect(admin_url('users'));
        }

        if (User::emailExists($email)) {
            Flash::error('Ya existe una cuenta con ese correo.');
            $this->redirect(admin_url('users'));
        }

        $userId = User::create($name, $email, $password, $request->text('role'));

        Auth::log('create', 'user', $userId, $email);
        $this->saved('Cuenta creada.');
        $this->redirect(admin_url('users'));
    }

    public function deleteUser(Request $request): void
    {
        $this->boot($request);
        $this->requireCsrf($request);
        $this->requireAdmin();

        $userId = (int) $request->param('id');

        if ($userId === Auth::id()) {
            Flash::error('No puedes eliminar tu propia cuenta.');
            $this->redirect(admin_url('users'));
        }

        User::delete($userId);
        Auth::log('delete', 'user', $userId);
        $this->saved('Cuenta eliminada.');
        $this->redirect(admin_url('users'));
    }

    public function activity(Request $request): void
    {
        $this->boot($request);

        $this->view('admin/activity', ['entries' => User::activityLog(100)]);
    }
}
