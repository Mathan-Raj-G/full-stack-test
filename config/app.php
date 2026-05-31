<?php

return [
    'name' => 'WPoets Full Stack Developer Test',
    'hero' => [
        'title' => 'DelphianLogic in Action',
        'subtitle' => 'Lorem ipsum dolor sit amet, consectetuer adipiscing elit. Aenean commodo',
    ],
    'uploads' => [
        'slides' => 'uploads/slides',
        'categories' => 'uploads/categories',
    ],
    'allowed_uploads' => [
        'slides' => [
            'mime_types' => ['image/jpeg', 'image/png', 'image/webp'],
            'extensions' => ['jpg', 'jpeg', 'png', 'webp'],
            'max_size' => 3 * 1024 * 1024,
        ],
        'categories' => [
            'mime_types' => ['image/svg+xml', 'image/jpeg', 'image/png', 'image/webp'],
            'extensions' => ['svg', 'jpg', 'jpeg', 'png', 'webp'],
            'max_size' => 2 * 1024 * 1024,
        ],
    ],
];
