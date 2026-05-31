<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Repositories\CategoryRepository;
use App\Repositories\SlideRepository;

final class HomeController extends BaseController
{
    public function index(): void
    {
        $categoryRepository = new CategoryRepository();
        $slideRepository = new SlideRepository();

        $categories = [];

        foreach ($categoryRepository->all(true) as $category) {
            $slides = $slideRepository->byCategory((int) $category['id'], true);

            if ($slides === []) {
                continue;
            }

            $category['slides'] = $slides;
            $categories[] = $category;
        }

        $activeCategoryId = $categories[0]['id'] ?? null;

        $this->render('home', [
            'pageTitle' => config('hero.title'),
            'bodyClass' => 'page-home',
            'heroTitle' => config('hero.title'),
            'heroSubtitle' => config('hero.subtitle'),
            'categories' => $categories,
            'activeCategoryId' => $activeCategoryId,
        ]);
    }
}
