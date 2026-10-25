<?php

declare(strict_types=1);

namespace App\Core;

abstract class Controller
{
    protected function view(string $template, array $data = [], string $layout = 'site'): void
    {
        echo View::render($template, $data, $layout);
    }

    protected function json(array $data, int $status = 200): void
    {
        http_response_code($status);
        header('Content-Type: application/json; charset=utf-8');
        echo json_encode($data, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
        exit;
    }

    protected function redirect(string $path): never
    {
        header('Location: ' . $path, true, 302);
        exit;
    }

    protected function back(): never
    {
        $this->redirect($_SERVER['HTTP_REFERER'] ?? url('/'));
    }

    protected function notFound(): void
    {
        http_response_code(404);
    }

    /**
     * Interrumpe la peticion si el token CSRF no es valido.
     */
    protected function requireCsrf(Request $request): void
    {
        if (Csrf::check($request->text('csrf_token'))) {
            return;
        }

        if ($request->isAjax()) {
            $this->json(['ok' => false, 'message' => t('msg.error')], 419);
        }

        Flash::error(t('msg.error'));
        $this->back();
    }
}
