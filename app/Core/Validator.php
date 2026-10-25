<?php

declare(strict_types=1);

namespace App\Core;

final class Validator
{
    private array $errors = [];

    public function __construct(private array $data)
    {
    }

    public function value(string $field): string
    {
        $value = $this->data[$field] ?? '';

        return is_scalar($value) ? trim((string) $value) : '';
    }

    public function required(string $field, string $message): self
    {
        if ($this->value($field) === '') {
            $this->addError($field, $message);
        }

        return $this;
    }

    public function email(string $field, string $message): self
    {
        $value = $this->value($field);

        if ($value !== '' && filter_var($value, FILTER_VALIDATE_EMAIL) === false) {
            $this->addError($field, $message);
        }

        return $this;
    }

    public function minLength(string $field, int $min, string $message): self
    {
        $value = $this->value($field);

        if ($value !== '' && mb_strlen($value) < $min) {
            $this->addError($field, $message);
        }

        return $this;
    }

    public function maxLength(string $field, int $max, string $message): self
    {
        if (mb_strlen($this->value($field)) > $max) {
            $this->addError($field, $message);
        }

        return $this;
    }

    public function accepted(string $field, string $message): self
    {
        if (!in_array($this->value($field), ['1', 'on', 'true', 'yes'], true)) {
            $this->addError($field, $message);
        }

        return $this;
    }

    public function in(string $field, array $allowed, string $message): self
    {
        $value = $this->value($field);

        if ($value !== '' && !in_array($value, $allowed, true)) {
            $this->addError($field, $message);
        }

        return $this;
    }

    public function addError(string $field, string $message): void
    {
        $this->errors[$field] ??= $message;
    }

    public function fails(): bool
    {
        return $this->errors !== [];
    }

    public function passes(): bool
    {
        return $this->errors === [];
    }

    public function errors(): array
    {
        return $this->errors;
    }

    public function firstError(): string
    {
        return (string) (reset($this->errors) ?: '');
    }
}
