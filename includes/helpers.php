<?php

if (!function_exists('base_path')) {
    function base_path(string $path = ''): string
    {
        $basePath = dirname(__DIR__);

        return $path === ''
            ? $basePath
            : $basePath . DIRECTORY_SEPARATOR . str_replace(['/', '\\'], DIRECTORY_SEPARATOR, $path);
    }
}

if (!function_exists('config')) {
    function config(?string $key = null, mixed $default = null): mixed
    {
        global $appConfig;

        if ($key === null) {
            return $appConfig;
        }

        $segments = explode('.', $key);
        $value = $appConfig;

        foreach ($segments as $segment) {
            if (!is_array($value) || !array_key_exists($segment, $value)) {
                return $default;
            }

            $value = $value[$segment];
        }

        return $value;
    }
}

if (!function_exists('app_root_uri')) {
    function app_root_uri(): string
    {
        static $rootUri = null;

        if ($rootUri !== null) {
            return $rootUri;
        }

        $scriptName = str_replace('\\', '/', $_SERVER['SCRIPT_NAME'] ?? '');
        $scriptDirectory = str_replace('\\', '/', dirname($scriptName));

        if (preg_match('#/admin$#', $scriptDirectory)) {
            $scriptDirectory = dirname($scriptDirectory);
        }

        $scriptDirectory = rtrim(str_replace('\\', '/', $scriptDirectory), '/.');
        $rootUri = $scriptDirectory === '' ? '' : $scriptDirectory;

        return $rootUri;
    }
}

if (!function_exists('url')) {
    function url(string $path = ''): string
    {
        $root = app_root_uri();
        $path = ltrim($path, '/');

        if ($root === '') {
            return $path === '' ? '/' : '/' . $path;
        }

        return $path === '' ? $root . '/' : $root . '/' . $path;
    }
}

if (!function_exists('asset')) {
    function asset(string $path = ''): string
    {
        return url($path);
    }
}

if (!function_exists('e')) {
    function e(?string $value): string
    {
        return htmlspecialchars((string) $value, ENT_QUOTES, 'UTF-8');
    }
}

if (!function_exists('old')) {
    function old(string $key, mixed $default = ''): mixed
    {
        $oldInput = $_SESSION['_old'] ?? [];

        return $oldInput[$key] ?? $default;
    }
}

if (!function_exists('set_old')) {
    function set_old(array $data): void
    {
        $_SESSION['_old'] = $data;
    }
}

if (!function_exists('clear_old')) {
    function clear_old(): void
    {
        unset($_SESSION['_old']);
    }
}

if (!function_exists('flash')) {
    function flash(string $key, mixed $value = null): mixed
    {
        if ($value !== null) {
            $_SESSION['_flash'][$key] = $value;

            return null;
        }

        $message = $_SESSION['_flash'][$key] ?? null;
        unset($_SESSION['_flash'][$key]);

        return $message;
    }
}

if (!function_exists('redirect')) {
    function redirect(string $path): never
    {
        header('Location: ' . url($path));
        exit;
    }
}

if (!function_exists('request_method_is')) {
    function request_method_is(string $method): bool
    {
        return strtoupper($_SERVER['REQUEST_METHOD'] ?? 'GET') === strtoupper($method);
    }
}

if (!function_exists('input')) {
    function input(string $key, mixed $default = ''): mixed
    {
        return $_POST[$key] ?? $_GET[$key] ?? $default;
    }
}

if (!function_exists('is_upload_path')) {
    function is_upload_path(?string $path): bool
    {
        return is_string($path) && str_starts_with(str_replace('\\', '/', $path), 'uploads/');
    }
}

if (!function_exists('absolute_public_path')) {
    function absolute_public_path(?string $relativePath): ?string
    {
        if (!$relativePath) {
            return null;
        }

        return base_path(str_replace(['\\'], '/', ltrim($relativePath, '/')));
    }
}
