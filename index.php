<?php

declare(strict_types=1);

require __DIR__ . '/includes/bootstrap.php';

use App\Controllers\HomeController;

(new HomeController())->index();
