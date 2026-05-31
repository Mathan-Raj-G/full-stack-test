<section class="admin-page-header mb-4">
    <div>
        <h1 class="admin-page-title mb-1">Categories</h1>
        <p class="admin-page-copy mb-0">Create and manage the tab or accordion groups used by the frontend slider.</p>
    </div>
</section>

<div class="row g-4 align-items-start admin-split-layout">
    <div class="col-xl-7">
        <div class="admin-card">
            <div class="admin-card__header">
                <div>
                    <h2 class="admin-card__title mb-1">Existing Categories</h2>
                    <p class="admin-card__copy mb-0">Status changes are applied immediately and reflected on the frontend.</p>
                </div>
            </div>
            <div class="table-responsive">
                <table class="table admin-table align-middle mb-0">
                    <thead>
                    <tr>
                        <th scope="col">Icon</th>
                        <th scope="col">Title</th>
                        <th scope="col">Slides</th>
                        <th scope="col">Status</th>
                        <th scope="col" class="text-end">Actions</th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php if ($categories === []): ?>
                        <tr>
                            <td colspan="5" class="text-center py-4">No categories have been created yet.</td>
                        </tr>
                    <?php else: ?>
                        <?php foreach ($categories as $category): ?>
                            <tr>
                                <td>
                                    <span class="admin-icon-preview">
                                        <img src="<?= e(asset($category['icon'])); ?>" alt="<?= e($category['title']); ?> icon" loading="lazy">
                                    </span>
                                </td>
                                <td><?= e($category['title']); ?></td>
                                <td><?= (int) $category['slide_count']; ?></td>
                                <td>
                                    <span class="badge rounded-pill text-bg-<?= (int) $category['status'] === 1 ? 'success' : 'secondary'; ?>">
                                        <?= (int) $category['status'] === 1 ? 'Active' : 'Inactive'; ?>
                                    </span>
                                </td>
                                <td>
                                    <div class="admin-table__actions">
                                        <a class="btn btn-sm btn-outline-primary" href="<?= e(url('admin/categories.php?edit=' . (int) $category['id'])); ?>">Edit</a>
                                        <form method="post" class="d-inline-block">
                                            <?= \App\Support\Csrf::inputField(); ?>
                                            <input type="hidden" name="form_action" value="toggle">
                                            <input type="hidden" name="id" value="<?= (int) $category['id']; ?>">
                                            <input type="hidden" name="status" value="<?= (int) $category['status'] === 1 ? 0 : 1; ?>">
                                            <button class="btn btn-sm btn-outline-secondary" type="submit">
                                                <?= (int) $category['status'] === 1 ? 'Deactivate' : 'Activate'; ?>
                                            </button>
                                        </form>
                                        <form method="post" class="d-inline-block" data-confirm-delete>
                                            <?= \App\Support\Csrf::inputField(); ?>
                                            <input type="hidden" name="form_action" value="delete">
                                            <input type="hidden" name="id" value="<?= (int) $category['id']; ?>">
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

    <div class="col-xl-5">
        <div class="admin-card">
            <div class="admin-card__header">
                <div>
                    <h2 class="admin-card__title mb-1"><?= $editingCategory ? 'Edit Category' : 'Create Category'; ?></h2>
                    <p class="admin-card__copy mb-0">Use an uploaded icon or a relative asset path such as <code>files/images/DL-learning.svg</code>.</p>
                </div>
            </div>

            <div class="admin-card__body">
                <form method="post" enctype="multipart/form-data" novalidate>
                    <?= \App\Support\Csrf::inputField(); ?>
                    <input type="hidden" name="form_action" value="<?= $editingCategory ? 'update' : 'create'; ?>">
                    <?php if ($editingCategory): ?>
                        <input type="hidden" name="id" value="<?= (int) $formData['id']; ?>">
                    <?php endif; ?>

                    <div class="mb-3">
                        <label class="form-label" for="categoryTitle">Title</label>
                        <input class="form-control<?= isset($errors['title']) ? ' is-invalid' : ''; ?>" id="categoryTitle" name="title" type="text" maxlength="120" value="<?= e((string) $formData['title']); ?>" required>
                        <?php if (isset($errors['title'])): ?>
                            <div class="invalid-feedback"><?= e($errors['title']); ?></div>
                        <?php endif; ?>
                    </div>

                    <div class="mb-3">
                        <label class="form-label" for="categoryIcon">Icon Path</label>
                        <input class="form-control<?= isset($errors['icon']) ? ' is-invalid' : ''; ?>" id="categoryIcon" name="icon" type="text" maxlength="255" value="<?= e((string) $formData['icon']); ?>" placeholder="files/images/DL-learning.svg">
                        <?php if (isset($errors['icon'])): ?>
                            <div class="invalid-feedback"><?= e($errors['icon']); ?></div>
                        <?php endif; ?>
                    </div>

                    <div class="mb-3">
                        <label class="form-label" for="categoryIconUpload">Upload Icon</label>
                        <input class="form-control" id="categoryIconUpload" name="icon_upload" type="file" accept=".svg,.jpg,.jpeg,.png,.webp">
                        <div class="form-text">Uploading a new file will replace the current icon path.</div>
                    </div>

                    <div class="mb-4">
                        <label class="form-label" for="categoryStatus">Status</label>
                        <select class="form-select" id="categoryStatus" name="status">
                            <option value="1" <?= (int) $formData['status'] === 1 ? 'selected' : ''; ?>>Active</option>
                            <option value="0" <?= (int) $formData['status'] === 0 ? 'selected' : ''; ?>>Inactive</option>
                        </select>
                    </div>

                    <?php if (!empty($formData['icon'])): ?>
                        <div class="admin-form-preview mb-4">
                            <span class="admin-form-preview__label">Current Icon</span>
                            <span class="admin-icon-preview admin-icon-preview--large">
                                <img src="<?= e(asset((string) $formData['icon'])); ?>" alt="Current category icon" loading="lazy">
                            </span>
                        </div>
                    <?php endif; ?>

                    <div class="d-flex flex-wrap gap-2">
                        <button class="btn btn-primary" type="submit"><?= $editingCategory ? 'Update Category' : 'Create Category'; ?></button>
                        <?php if ($editingCategory): ?>
                            <a class="btn btn-outline-secondary" href="<?= e(url('admin/categories.php')); ?>">Cancel</a>
                        <?php endif; ?>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
