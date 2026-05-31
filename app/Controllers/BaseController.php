<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Support\Csrf;
use App\Support\View;
use RuntimeException;

abstract class BaseController
{
    protected function render(string $view, array $data = [], string $layout = 'main'): void
    {
        View::render($view, $data, $layout);
    }

    protected function ensureValidCsrf(): void
    {
        if (!Csrf::validate($_POST['_token'] ?? null)) {
            throw new RuntimeException('The form session has expired. Please refresh the page and try again.');
        }
    }

    protected function success(string $message): void
    {
        flash('status', ['type' => 'success', 'message' => $message]);
    }

    protected function error(string $message): void
    {
        flash('status', ['type' => 'danger', 'message' => $message]);
    }
}
