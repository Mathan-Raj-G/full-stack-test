<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Repositories\CategoryRepository;
use App\Repositories\SlideRepository;
use App\Services\UploadService;
use App\Support\Validator;
use RuntimeException;

final class AdminSlideController extends BaseController
{
    private SlideRepository $slideRepository;

    private CategoryRepository $categoryRepository;

    private UploadService $uploadService;

    public function __construct()
    {
        $this->slideRepository = new SlideRepository();
        $this->categoryRepository = new CategoryRepository();
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

        $editingSlide = null;
        $editId = filter_input(INPUT_GET, 'edit', FILTER_VALIDATE_INT);

        if ($editId) {
            $editingSlide = $this->slideRepository->find((int) $editId);

            if ($editingSlide && $errors === []) {
                $formData = [
                    'id' => (int) $editingSlide['id'],
                    'category_id' => (int) $editingSlide['category_id'],
                    'badge_text' => $editingSlide['badge_text'],
                    'title' => $editingSlide['title'],
                    'description' => $editingSlide['description'],
                    'button_text' => $editingSlide['button_text'],
                    'image' => $editingSlide['image'],
                    'sort_order' => (int) $editingSlide['sort_order'],
                    'status' => (int) $editingSlide['status'],
                ];
            }
        }

        $this->render('admin/slides/index', [
            'pageTitle' => 'Manage Slides',
            'bodyClass' => 'page-admin',
            'slides' => $this->slideRepository->all(),
            'categories' => $this->categoryRepository->all(),
            'editingSlide' => $editingSlide,
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
            default => throw new RuntimeException('Unsupported slide action requested.'),
        };
    }

    private function store(): array
    {
        $validator = new Validator($_POST);
        $categoryId = $validator->integer('category_id', 'Category', true, 1);
        $badgeText = $validator->requiredText('badge_text', 'Badge text', 120);
        $title = $validator->requiredText('title', 'Slide title', 180);
        $description = $validator->optionalText('description', 'Description', 320);
        $buttonText = $validator->requiredText('button_text', 'Button text', 60);
        $sortOrder = $validator->integer('sort_order', 'Sort order', true, 1);
        $status = $validator->booleanStatus('status');
        $hasUpload = !empty($_FILES['image_upload']['name']);

        $errors = $validator->errors();

        if ($hasUpload && $errors === []) {
            $imagePath = $this->uploadService->store($_FILES['image_upload'], 'slides');
        } else {
            $imagePath = trim((string) ($_POST['image'] ?? ''));
        }

        if (!$hasUpload && $imagePath === '') {
            $errors['image'] = 'Slide image is required.';
        }

        if ($categoryId && !$this->categoryRepository->find($categoryId)) {
            $errors['category_id'] = 'Please select a valid category.';
        }

        $formData = [
            'id' => null,
            'category_id' => $categoryId ?? 0,
            'badge_text' => $badgeText ?? '',
            'title' => $title ?? '',
            'description' => $description,
            'button_text' => $buttonText ?? '',
            'image' => $imagePath,
            'sort_order' => $sortOrder ?? 1,
            'status' => $status,
        ];

        if ($errors !== []) {
            return [$errors, $formData];
        }

        $this->slideRepository->create([
            'category_id' => $categoryId,
            'badge_text' => $badgeText,
            'title' => $title,
            'description' => $description,
            'button_text' => $buttonText,
            'image' => $imagePath,
            'sort_order' => $sortOrder,
            'status' => $status,
        ]);

        $this->success('Slide created successfully.');
        redirect('admin/slides.php');
    }

    private function update(): array
    {
        $validator = new Validator($_POST);
        $id = $validator->integer('id', 'Slide', true, 1);
        $categoryId = $validator->integer('category_id', 'Category', true, 1);
        $badgeText = $validator->requiredText('badge_text', 'Badge text', 120);
        $title = $validator->requiredText('title', 'Slide title', 180);
        $description = $validator->optionalText('description', 'Description', 320);
        $buttonText = $validator->requiredText('button_text', 'Button text', 60);
        $sortOrder = $validator->integer('sort_order', 'Sort order', true, 1);
        $status = $validator->booleanStatus('status');

        $existingSlide = $id ? $this->slideRepository->find($id) : null;
        $hasUpload = !empty($_FILES['image_upload']['name']);

        if (!$existingSlide) {
            throw new RuntimeException('The selected slide could not be found.');
        }

        $errors = $validator->errors();

        if ($hasUpload && $errors === []) {
            $imagePath = $this->uploadService->store($_FILES['image_upload'], 'slides', $existingSlide['image']);
        } else {
            $imagePath = trim((string) ($_POST['image'] ?? ''));
        }

        if ($imagePath === '') {
            $imagePath = $existingSlide['image'];
        }

        if ($imagePath === '') {
            $errors['image'] = 'Slide image is required.';
        }

        if ($categoryId && !$this->categoryRepository->find($categoryId)) {
            $errors['category_id'] = 'Please select a valid category.';
        }

        $formData = [
            'id' => $id,
            'category_id' => $categoryId ?? 0,
            'badge_text' => $badgeText ?? '',
            'title' => $title ?? '',
            'description' => $description,
            'button_text' => $buttonText ?? '',
            'image' => $imagePath,
            'sort_order' => $sortOrder ?? 1,
            'status' => $status,
        ];

        if ($errors !== []) {
            return [$errors, $formData];
        }

        $this->slideRepository->update($id, [
            'category_id' => $categoryId,
            'badge_text' => $badgeText,
            'title' => $title,
            'description' => $description,
            'button_text' => $buttonText,
            'image' => $imagePath,
            'sort_order' => $sortOrder,
            'status' => $status,
        ]);

        $this->success('Slide updated successfully.');
        redirect('admin/slides.php');
    }

    private function delete(): array
    {
        $id = filter_input(INPUT_POST, 'id', FILTER_VALIDATE_INT);

        if (!$id) {
            throw new RuntimeException('Invalid slide selected.');
        }

        $slide = $this->slideRepository->find((int) $id);

        if (!$slide) {
            throw new RuntimeException('Slide not found.');
        }

        $this->uploadService->deleteManagedFile($slide['image']);
        $this->slideRepository->delete((int) $id);

        $this->success('Slide deleted successfully.');
        redirect('admin/slides.php');
    }

    private function toggle(): array
    {
        $id = filter_input(INPUT_POST, 'id', FILTER_VALIDATE_INT);
        $status = filter_input(INPUT_POST, 'status', FILTER_VALIDATE_INT);

        if (!$id || !in_array($status, [0, 1], true)) {
            throw new RuntimeException('Invalid slide status request.');
        }

        $this->slideRepository->setStatus((int) $id, (int) $status);
        $this->success('Slide status updated successfully.');
        redirect('admin/slides.php');
    }

    private function defaultFormData(): array
    {
        return [
            'id' => null,
            'category_id' => 0,
            'badge_text' => '',
            'title' => '',
            'description' => '',
            'button_text' => 'Learn More',
            'image' => '',
            'sort_order' => 1,
            'status' => 1,
        ];
    }
}
