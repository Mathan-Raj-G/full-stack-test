<?php

declare(strict_types=1);

session_start();

require_once __DIR__ . '/helpers.php';

$appConfig = require base_path('config/app.php');

spl_autoload_register(static function (string $class): void {
    $prefix = 'App\\';

    if (!str_starts_with($class, $prefix)) {
        return;
    }

    $relativeClass = substr($class, strlen($prefix));
    $file = base_path('app/' . str_replace('\\', '/', $relativeClass) . '.php');

    if (is_file($file)) {
        require_once $file;
    }
});

date_default_timezone_set('Asia/Kolkata');
