<?php

declare(strict_types=1);

namespace App\Core;

final class Flash
{
    private const KEY = '_flash';

    public static function add(string $type, string $message): void
    {
        $messages   = Session::get(self::KEY, []);
        $messages[] = ['type' => $type, 'message' => $message];

        Session::set(self::KEY, $messages);
    }

    public static function success(string $message): void
    {
        self::add('success', $message);
    }

    public static function error(string $message): void
    {
        self::add('error', $message);
    }

    public static function info(string $message): void
    {
        self::add('info', $message);
    }

    /**
     * Devuelve los mensajes pendientes y los vacia.
     */
    public static function pull(): array
    {
        $messages = Session::get(self::KEY, []);
        Session::forget(self::KEY);

        return $messages;
    }

    public static function keepInput(array $input): void
    {
        unset($input['csrf_token'], $input['password'], $input['password_confirm']);
        Session::set('_old_input', $input);
    }

    public static function old(string $field, string $default = ''): string
    {
        $input = Session::get('_old_input', []);

        return isset($input[$field]) && is_scalar($input[$field])
            ? (string) $input[$field]
            : $default;
    }

    public static function clearInput(): void
    {
        Session::forget('_old_input');
    }

    public static function setErrors(array $errors): void
    {
        Session::set('_errors', $errors);
    }

    public static function errors(): array
    {
        $errors = Session::get('_errors', []);
        Session::forget('_errors');

        return $errors;
    }
}
