<?php
/** @var string $pageTitle */
/** @var string $viewFile */
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= e($pageTitle ?? config('name')); ?> | <?= e(config('name')); ?></title>
    <meta name="description" content="Responsive PHP and MySQL slider showcase built for the WPoets Full Stack Developer Test.">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Open+Sans:wght@400;500;600;700&family=Titillium+Web:wght@400;600;700&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css" rel="stylesheet">
    <link href="<?= e(asset('assets/css/style.css')); ?>" rel="stylesheet">
</head>
<body class="<?= e($bodyClass ?? ''); ?>" data-app-root="<?= e(url('')); ?>">
    <main>
        <?php require $viewFile; ?>
    </main>

    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js"></script>
    <script src="<?= e(asset('assets/js/app.js')); ?>"></script>
</body>
</html>
