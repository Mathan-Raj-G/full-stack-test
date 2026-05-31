<?php

declare(strict_types=1);

require dirname(__DIR__) . '/includes/bootstrap.php';

use App\Controllers\AdminCategoryController;

(new AdminCategoryController())->index();
