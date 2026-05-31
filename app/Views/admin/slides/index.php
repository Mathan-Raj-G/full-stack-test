<section class="admin-page-header mb-4">
    <div>
        <h1 class="admin-page-title mb-1">Slides</h1>
        <p class="admin-page-copy mb-0">Manage card content, button labels, images, visibility, and the display order within each category.</p>
    </div>
</section>

<div class="row g-4 align-items-start admin-split-layout">
    <div class="col-xl-8">
        <div class="admin-card">
            <div class="admin-card__header">
                <div>
                    <h2 class="admin-card__title mb-1">Existing Slides</h2>
                    <p class="admin-card__copy mb-0">Slides are ordered by category and sort order to match the frontend experience.</p>
                </div>
            </div>
            <div class="table-responsive">
                <table class="table admin-table align-middle mb-0">
                    <thead>
                    <tr>
                        <th scope="col">Preview</th>
                        <th scope="col">Title</th>
                        <th scope="col">Category</th>
                        <th scope="col">Sort</th>
                        <th scope="col">Status</th>
                        <th scope="col" class="text-end">Actions</th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php if ($slides === []): ?>
                        <tr>
                            <td colspan="6" class="text-center py-4">No slides have been created yet.</td>
                        </tr>
                    <?php else: ?>
                        <?php foreach ($slides as $slide): ?>
                            <tr>
                                <td>
                                    <span class="admin-image-preview">
                                        <img src="<?= e(asset($slide['image'])); ?>" alt="<?= e($slide['title']); ?>" loading="lazy">
                                    </span>
                                </td>
                                <td>
                                    <strong class="d-block"><?= e($slide['title']); ?></strong>
                                    <span class="text-muted small"><?= e($slide['badge_text']); ?></span>
                                </td>
                                <td><?= e($slide['category_title']); ?></td>
                                <td><?= (int) $slide['sort_order']; ?></td>
                                <td>
                                    <span class="badge rounded-pill text-bg-<?= (int) $slide['status'] === 1 ? 'success' : 'secondary'; ?>">
                                        <?= (int) $slide['status'] === 1 ? 'Active' : 'Inactive'; ?>
                                    </span>
                                </td>
                                <td>
                                    <div class="admin-table__actions">
                                        <a class="btn btn-sm btn-outline-primary" href="<?= e(url('admin/slides.php?edit=' . (int) $slide['id'])); ?>">Edit</a>
                                        <form method="post" class="d-inline-block">
                                            <?= \App\Support\Csrf::inputField(); ?>
                                            <input type="hidden" name="form_action" value="toggle">
                                            <input type="hidden" name="id" value="<?= (int) $slide['id']; ?>">
                                            <input type="hidden" name="status" value="<?= (int) $slide['status'] === 1 ? 0 : 1; ?>">
                                            <button class="btn btn-sm btn-outline-secondary" type="submit">
                                                <?= (int) $slide['status'] === 1 ? 'Deactivate' : 'Activate'; ?>
                                            </button>
                                        </form>
                                        <form method="post" class="d-inline-block" data-confirm-delete>
                                            <?= \App\Support\Csrf::inputField(); ?>
                                            <input type="hidden" name="form_action" value="delete">
                                            <input type="hidden" name="id" value="<?= (int) $slide['id']; ?>">
                                            <button class="btn btn-sm btn-outline-danger" type="submit">Delete</button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <div class="col-xl-4">
        <div class="admin-card">
            <div class="admin-card__header">
                <div>
                    <h2 class="admin-card__title mb-1"><?= $editingSlide ? 'Edit Slide' : 'Create Slide'; ?></h2>
                    <p class="admin-card__copy mb-0">Use an uploaded image or a relative asset path such as <code>files/images/DL-Learning-1.jpg</code>.</p>
                </div>
            </div>

            <div class="admin-card__body">
                <form method="post" enctype="multipart/form-data" novalidate>
                    <?= \App\Support\Csrf::inputField(); ?>
                    <input type="hidden" name="form_action" value="<?= $editingSlide ? 'update' : 'create'; ?>">
                    <?php if ($editingSlide): ?>
                        <input type="hidden" name="id" value="<?= (int) $formData['id']; ?>">
                    <?php endif; ?>

                    <div class="mb-3">
                        <label class="form-label" for="slideCategory">Category</label>
                        <select class="form-select<?= isset($errors['category_id']) ? ' is-invalid' : ''; ?>" id="slideCategory" name="category_id" required>
                            <option value="">Select category</option>
                            <?php foreach ($categories as $category): ?>
                                <option value="<?= (int) $category['id']; ?>" <?= (int) $formData['category_id'] === (int) $category['id'] ? 'selected' : ''; ?>>
                                    <?= e($category['title']); ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                        <?php if (isset($errors['category_id'])): ?>
                            <div class="invalid-feedback"><?= e($errors['category_id']); ?></div>
                        <?php endif; ?>
                    </div>

                    <div class="mb-3">
                        <label class="form-label" for="slideBadge">Badge Text</label>
                        <input class="form-control<?= isset($errors['badge_text']) ? ' is-invalid' : ''; ?>" id="slideBadge" name="badge_text" type="text" maxlength="120" value="<?= e((string) $formData['badge_text']); ?>" required>
                        <?php if (isset($errors['badge_text'])): ?>
                            <div class="invalid-feedback"><?= e($errors['badge_text']); ?></div>
                        <?php endif; ?>
                    </div>

                    <div class="mb-3">
                        <label class="form-label" for="slideTitle">Title</label>
                        <textarea class="form-control<?= isset($errors['title']) ? ' is-invalid' : ''; ?>" id="slideTitle" name="title" rows="3" maxlength="180" required><?= e((string) $formData['title']); ?></textarea>
                        <?php if (isset($errors['title'])): ?>
                            <div class="invalid-feedback"><?= e($errors['title']); ?></div>
                        <?php endif; ?>
                    </div>

                    <div class="mb-3">
                        <label class="form-label" for="slideDescription">Description</label>
                        <textarea class="form-control<?= isset($errors['description']) ? ' is-invalid' : ''; ?>" id="slideDescription" name="description" rows="2" maxlength="320"><?= e((string) $formData['description']); ?></textarea>
                        <?php if (isset($errors['description'])): ?>
                            <div class="invalid-feedback"><?= e($errors['description']); ?></div>
                        <?php endif; ?>
                    </div>

                    <div class="mb-3">
                        <label class="form-label" for="slideButton">Button Text</label>
                        <input class="form-control<?= isset($errors['button_text']) ? ' is-invalid' : ''; ?>" id="slideButton" name="button_text" type="text" maxlength="60" value="<?= e((string) $formData['button_text']); ?>" required>
                        <?php if (isset($errors['button_text'])): ?>
                            <div class="invalid-feedback"><?= e($errors['button_text']); ?></div>
                        <?php endif; ?>
                    </div>

                    <div class="row g-3">
                        <div class="col-sm-6">
                            <label class="form-label" for="slideSortOrder">Sort Order</label>
                            <input class="form-control<?= isset($errors['sort_order']) ? ' is-invalid' : ''; ?>" id="slideSortOrder" name="sort_order" type="number" min="1" value="<?= (int) $formData['sort_order']; ?>" required>
                            <?php if (isset($errors['sort_order'])): ?>
                                <div class="invalid-feedback"><?= e($errors['sort_order']); ?></div>
                            <?php endif; ?>
                        </div>
                        <div class="col-sm-6">
                            <label class="form-label" for="slideStatus">Status</label>
                            <select class="form-select" id="slideStatus" name="status">
                                <option value="1" <?= (int) $formData['status'] === 1 ? 'selected' : ''; ?>>Active</option>
                                <option value="0" <?= (int) $formData['status'] === 0 ? 'selected' : ''; ?>>Inactive</option>
                            </select>
                        </div>
                    </div>

                    <div class="mt-3 mb-3">
                        <label class="form-label" for="slideImage">Image Path</label>
                        <input class="form-control<?= isset($errors['image']) ? ' is-invalid' : ''; ?>" id="slideImage" name="image" type="text" maxlength="255" value="<?= e((string) $formData['image']); ?>" placeholder="files/images/DL-Learning-1.jpg">
                        <?php if (isset($errors['image'])): ?>
                            <div class="invalid-feedback"><?= e($errors['image']); ?></div>
                        <?php endif; ?>
                    </div>

                    <div class="mb-4">
                        <label class="form-label" for="slideImageUpload">Upload Image</label>
                        <input class="form-control" id="slideImageUpload" name="image_upload" type="file" accept=".jpg,.jpeg,.png,.webp">
                        <div class="form-text">Uploading a new file will replace the current image path.</div>
                    </div>

                    <?php if (!empty($formData['image'])): ?>
                        <div class="admin-form-preview mb-4">
                            <span class="admin-form-preview__label">Current Image</span>
                            <span class="admin-image-preview admin-image-preview--large">
                                <img src="<?= e(asset((string) $formData['image'])); ?>" alt="Current slide image" loading="lazy">
                            </span>
                        </div>
                    <?php endif; ?>

                    <div class="d-flex flex-wrap gap-2">
                        <button class="btn btn-primary" type="submit"><?= $editingSlide ? 'Update Slide' : 'Create Slide'; ?></button>
                        <?php if ($editingSlide): ?>
                            <a class="btn btn-outline-secondary" href="<?= e(url('admin/slides.php')); ?>">Cancel</a>
                        <?php endif; ?>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
