<?php

declare(strict_types=1);

namespace App\Support;

use RuntimeException;

final class View
{
    public static function render(string $view, array $data = [], string $layout = 'main'): void
    {
        $viewFile = base_path('app/Views/' . $view . '.php');
        $layoutFile = base_path('app/Views/layouts/' . $layout . '.php');

        if (!is_file($viewFile)) {
            throw new RuntimeException(sprintf('View file [%s] was not found.', $viewFile));
        }

        if (!is_file($layoutFile)) {
            throw new RuntimeException(sprintf('Layout file [%s] was not found.', $layoutFile));
        }

        extract($data, EXTR_SKIP);

        require $layoutFile;
    }
}
