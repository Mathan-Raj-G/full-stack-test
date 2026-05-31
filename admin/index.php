<?php

declare(strict_types=1);

require dirname(__DIR__) . '/includes/bootstrap.php';

use App\Controllers\AdminDashboardController;

(new AdminDashboardController())->index();
