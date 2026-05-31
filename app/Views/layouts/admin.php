<?php
/** @var string $pageTitle */
/** @var string $viewFile */

$currentPage = basename($_SERVER['PHP_SELF'] ?? '');
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= e($pageTitle ?? 'Admin'); ?> | <?= e(config('name')); ?></title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Open+Sans:wght@400;500;600;700&family=Titillium+Web:wght@400;600;700&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="<?= e(asset('assets/css/admin.css')); ?>" rel="stylesheet">
</head>
<body class="<?= e($bodyClass ?? 'page-admin'); ?>">
    <header class="admin-header shadow-sm">
        <nav class="navbar navbar-expand-lg">
            <div class="container-xl">
                <a class="navbar-brand" href="<?= e(url('admin/index.php')); ?>">WPoets Admin</a>
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#adminNav" aria-controls="adminNav" aria-expanded="false" aria-label="Toggle admin navigation">
                    <span class="navbar-toggler-icon"></span>
                </button>
                <div class="collapse navbar-collapse" id="adminNav">
                    <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                        <li class="nav-item">
                            <a class="nav-link <?= $currentPage === 'index.php' ? 'active' : ''; ?>" href="<?= e(url('admin/index.php')); ?>">Dashboard</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link <?= $currentPage === 'categories.php' ? 'active' : ''; ?>" href="<?= e(url('admin/categories.php')); ?>">Categories</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link <?= $currentPage === 'slides.php' ? 'active' : ''; ?>" href="<?= e(url('admin/slides.php')); ?>">Slides</a>
                        </li>
                    </ul>
                    <a class="btn btn-outline-light btn-sm" href="<?= e(url('index.php')); ?>" target="_blank" rel="noopener">View Frontend</a>
                </div>
            </div>
        </nav>
    </header>

    <main class="admin-main py-4 py-lg-5">
        <div class="container-xl">
            <?php require base_path('app/Views/partials/flash.php'); ?>
            <?php require $viewFile; ?>
        </div>
    </main>

    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="<?= e(asset('assets/js/admin.js')); ?>"></script>
</body>
</html>
