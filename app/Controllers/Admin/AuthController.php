<?php

declare(strict_types=1);

namespace App\Controllers\Admin;

use App\Core\Auth;
use App\Core\Controller;
use App\Core\Csrf;
use App\Core\Flash;
use App\Core\Request;
use App\Core\Session;
use App\Core\Settings;
use App\Core\View;

final class AuthController extends Controller
{
    public function showLogin(Request $request): void
    {
        if (Auth::check()) {
            $this->redirect(admin_url());
        }

        echo View::render('admin/login', [
            'siteName' => Settings::get('site_name', 'AMÉLIE'),
            'flashes'  => Flash::pull(),
            'email'    => Flash::old('email'),
        ], 'login');
    }

    public function login(Request $request): void
    {
        if (!Csrf::check($request->text('csrf_token'))) {
            Flash::error('La sesión ha caducado. Vuelve a intentarlo.');
            $this->redirect(admin_url('login'));
        }

        $email    = mb_strtolower($request->text('email'));
        $password = (string) $request->input('password', '');

        if ($email === '' || $password === '') {
            Flash::error('Escribe tu correo y tu contraseña.');
            Flash::keepInput(['email' => $email]);
            $this->redirect(admin_url('login'));
        }

        if (Auth::attempt($email, $password)) {
            Flash::clearInput();
            Auth::log('login', 'user', Auth::id(), 'Acceso al panel');
            $this->redirect(admin_url());
        }

        if (Auth::lockedUntil($email) !== null) {
            Flash::error('Demasiados intentos fallidos. Prueba de nuevo dentro de 15 minutos.');
        } else {
            Flash::error('El correo o la contraseña no son correctos.');
        }

        Flash::keepInput(['email' => $email]);
        $this->redirect(admin_url('login'));
    }

    public function logout(Request $request): void
    {
        if (Csrf::check($request->text('csrf_token'))) {
            Auth::log('logout', 'user', Auth::id(), 'Salida del panel');
            Auth::logout();
            Session::destroy();
        }

        $this->redirect(admin_url('login'));
    }
}
