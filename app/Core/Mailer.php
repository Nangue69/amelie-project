<?php

declare(strict_types=1);

namespace App\Core;

final class Mailer
{
    /**
     * Envia un correo HTML. Devuelve false sin interrumpir si el servidor no
     * tiene salida de correo configurada, para no romper el flujo de compra.
     */
    public static function send(string $to, string $subject, string $htmlBody): bool
    {
        if (filter_var($to, FILTER_VALIDATE_EMAIL) === false) {
            return false;
        }

        $fromName  = Config::get('mail.from_name', 'AMELIE');
        $fromEmail = Config::get('mail.from_email', Settings::get('contact_email', 'hola@amelie.com'));

        $headers = implode("\r\n", [
            'MIME-Version: 1.0',
            'Content-Type: text/html; charset=UTF-8',
            'From: =?UTF-8?B?' . base64_encode((string) $fromName) . '?= <' . $fromEmail . '>',
            'Reply-To: ' . $fromEmail,
        ]);

        $encodedSubject = '=?UTF-8?B?' . base64_encode($subject) . '?=';

        try {
            return @mail($to, $encodedSubject, self::wrap($htmlBody), $headers);
        } catch (\Throwable $e) {
            error_log('Envio de correo fallido: ' . $e->getMessage());

            return false;
        }
    }

    public static function toAdmin(string $subject, string $htmlBody): bool
    {
        return self::send(Settings::get('contact_email', 'hola@amelie.com'), $subject, $htmlBody);
    }

    private static function wrap(string $body): string
    {
        $siteName = e(Settings::get('site_name', 'AMELIE'));

        return '<!doctype html><html lang="es"><head><meta charset="utf-8"></head>'
            . '<body style="margin:0;padding:24px;background:#F6F0E6;font-family:Georgia,serif;color:#2A2024;">'
            . '<div style="max-width:600px;margin:0 auto;background:#fff;padding:32px;border-top:3px solid #C4A36A;">'
            . '<h1 style="font-size:20px;letter-spacing:.2em;color:#651D36;margin:0 0 24px;">' . $siteName . '</h1>'
            . $body
            . '<p style="margin-top:32px;font-size:12px;color:#8A7F84;">' . $siteName . ' — More Than Lingerie</p>'
            . '</div></body></html>';
    }
}
