<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Repositories\CategoryRepository;
use App\Repositories\SlideRepository;
use App\Services\UploadService;
use App\Support\Validator;
use RuntimeException;

final class AdminCategoryController extends BaseController
{
    private CategoryRepository $categoryRepository;

    private SlideRepository $slideRepository;

    private UploadService $uploadService;

    public function __construct()
    {
        $this->categoryRepository = new CategoryRepository();
        $this->slideRepository = new SlideRepository();
        $this->uploadService = new UploadService();
    }

    public function index(): void
    {
        $errors = [];
        $formData = $this->defaultFormData();

        try {
            if (request_method_is('POST')) {
                [$errors, $formData] = $this->handlePost();
            }
        } catch (RuntimeException $exception) {
            $this->error($exception->getMessage());
        }

        $editingCategory = null;
        $editId = filter_input(INPUT_GET, 'edit', FILTER_VALIDATE_INT);

        if ($editId) {
            $editingCategory = $this->categoryRepository->find((int) $editId);

            if ($editingCategory && $errors === []) {
                $formData = [
                    'id' => (int) $editingCategory['id'],
                    'title' => $editingCategory['title'],
                    'icon' => $editingCategory['icon'],
                    'status' => (int) $editingCategory['status'],
                ];
            }
        }

        $this->render('admin/categories/index', [
            'pageTitle' => 'Manage Categories',
            'bodyClass' => 'page-admin',
            'categories' => $this->categoryRepository->allWithSlideCount(),
            'editingCategory' => $editingCategory,
            'formData' => $formData,
            'errors' => $errors,
        ], 'admin');
    }

    private function handlePost(): array
    {
        $this->ensureValidCsrf();

        $action = (string) ($_POST['form_action'] ?? '');

        return match ($action) {
            'create' => $this->store(),
            'update' => $this->update(),
            'delete' => $this->delete(),
            'toggle' => $this->toggle(),
            default => throw new RuntimeException('Unsupported category action requested.'),
        };
    }

    private function store(): array
    {
        $validator = new Validator($_POST);
        $title = $validator->requiredText('title', 'Category title', 120);
        $iconPath = $validator->optionalText('icon', 'Icon path', 255);
        $status = $validator->booleanStatus('status');
        $hasUpload = !empty($_FILES['icon_upload']['name']);
        $errors = $validator->errors();

        if ($hasUpload && $errors === []) {
            $iconPath = $this->uploadService->store($_FILES['icon_upload'], 'categories');
        }

        if (!$hasUpload && $iconPath === '') {
            $errors['icon'] = 'Category icon is required.';
        }

        $formData = [
            'id' => null,
            'title' => $title ?? '',
            'icon' => $iconPath,
            'status' => $status,
        ];

        if ($errors !== []) {
            return [$errors, $formData];
        }

        $this->categoryRepository->create([
            'title' => $title,
            'icon' => $iconPath,
            'status' => $status,
        ]);

        $this->success('Category created successfully.');
        redirect('admin/categories.php');
    }

    private function update(): array
    {
        $validator = new Validator($_POST);
        $id = $validator->integer('id', 'Category', true, 1);
        $title = $validator->requiredText('title', 'Category title', 120);
        $iconPath = $validator->optionalText('icon', 'Icon path', 255);
        $status = $validator->booleanStatus('status');
        $existingCategory = $id ? $this->categoryRepository->find($id) : null;
        $hasUpload = !empty($_FILES['icon_upload']['name']);

        if (!$existingCategory) {
            throw new RuntimeException('The selected category could not be found.');
        }

        $errors = $validator->errors();

        if ($hasUpload && $errors === []) {
            $iconPath = $this->uploadService->store($_FILES['icon_upload'], 'categories', $existingCategory['icon']);
        } elseif ($iconPath === '') {
            $iconPath = $existingCategory['icon'];
        }

        if ($iconPath === '') {
            $errors['icon'] = 'Category icon is required.';
        }

        $formData = [
            'id' => $id,
            'title' => $title ?? '',
            'icon' => $iconPath,
            'status' => $status,
        ];

        if ($errors !== []) {
            return [$errors, $formData];
        }

        $this->categoryRepository->update($id, [
            'title' => $title,
            'icon' => $iconPath,
            'status' => $status,
        ]);

        $this->success('Category updated successfully.');
        redirect('admin/categories.php');
    }

    private function delete(): array
    {
        $id = filter_input(INPUT_POST, 'id', FILTER_VALIDATE_INT);

        if (!$id) {
            throw new RuntimeException('Invalid category selected.');
        }

        $category = $this->categoryRepository->find((int) $id);

        if (!$category) {
            throw new RuntimeException('Category not found.');
        }

        foreach ($this->slideRepository->byCategory((int) $id) as $slide) {
            $this->uploadService->deleteManagedFile($slide['image']);
        }

        $this->uploadService->deleteManagedFile($category['icon']);
        $this->categoryRepository->delete((int) $id);

        $this->success('Category deleted successfully.');
        redirect('admin/categories.php');
    }

    private function toggle(): array
    {
        $id = filter_input(INPUT_POST, 'id', FILTER_VALIDATE_INT);
        $status = filter_input(INPUT_POST, 'status', FILTER_VALIDATE_INT);

        if (!$id || !in_array($status, [0, 1], true)) {
            throw new RuntimeException('Invalid category status request.');
        }

        $this->categoryRepository->setStatus((int) $id, (int) $status);
        $this->success('Category status updated successfully.');
        redirect('admin/categories.php');
    }

    private function defaultFormData(): array
    {
        return [
            'id' => null,
            'title' => '',
            'icon' => '',
            'status' => 1,
        ];
    }
}
