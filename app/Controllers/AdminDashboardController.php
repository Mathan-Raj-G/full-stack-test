<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Repositories\CategoryRepository;
use App\Repositories\SlideRepository;

final class AdminDashboardController extends BaseController
{
    public function index(): void
    {
        $categoryRepository = new CategoryRepository();
        $slideRepository = new SlideRepository();

        $this->render('admin/dashboard', [
            'pageTitle' => 'Admin Dashboard',
            'bodyClass' => 'page-admin',
            'categoryCount' => $categoryRepository->totalCount(),
            'slideCount' => $slideRepository->totalCount(),
            'recentSlides' => $slideRepository->recent(),
        ], 'admin');
    }
}
