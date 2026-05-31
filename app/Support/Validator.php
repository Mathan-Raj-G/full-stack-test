<?php

declare(strict_types=1);

namespace App\Support;

final class Validator
{
    private array $input;

    private array $errors = [];

    public function __construct(array $input)
    {
        $this->input = $input;
    }

    public function requiredText(string $field, string $label, int $maxLength = 255): ?string
    {
        $value = trim((string) ($this->input[$field] ?? ''));

        if ($value === '') {
            $this->errors[$field] = $label . ' is required.';

            return null;
        }

        if (mb_strlen($value) > $maxLength) {
            $this->errors[$field] = sprintf('%s must not be longer than %d characters.', $label, $maxLength);

            return null;
        }

        return $value;
    }

    public function optionalText(string $field, string $label, int $maxLength = 255): string
    {
        $value = trim((string) ($this->input[$field] ?? ''));

        if ($value !== '' && mb_strlen($value) > $maxLength) {
            $this->errors[$field] = sprintf('%s must not be longer than %d characters.', $label, $maxLength);
        }

        return $value;
    }

    public function integer(string $field, string $label, bool $required = true, ?int $min = null): ?int
    {
        $rawValue = $this->input[$field] ?? null;

        if ($rawValue === null || $rawValue === '') {
            if ($required) {
                $this->errors[$field] = $label . ' is required.';
            }

            return null;
        }

        if (filter_var($rawValue, FILTER_VALIDATE_INT) === false) {
            $this->errors[$field] = $label . ' must be a valid number.';

            return null;
        }

        $value = (int) $rawValue;

        if ($min !== null && $value < $min) {
            $this->errors[$field] = sprintf('%s must be at least %d.', $label, $min);
        }

        return $value;
    }

    public function booleanStatus(string $field, string $label = 'Status'): int
    {
        $value = (string) ($this->input[$field] ?? '1');

        if (!in_array($value, ['0', '1'], true)) {
            $this->errors[$field] = $label . ' must be either active or inactive.';

            return 1;
        }

        return (int) $value;
    }

    public function errors(): array
    {
        return $this->errors;
    }

    public function passes(): bool
    {
        return $this->errors === [];
    }
}
