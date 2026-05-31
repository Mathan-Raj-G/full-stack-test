<section class="admin-page-header mb-4">
    <div>
        <h1 class="admin-page-title mb-1">Dashboard</h1>
        <p class="admin-page-copy mb-0">Quick overview of the slider content currently configured in the application.</p>
    </div>
</section>

<div class="row g-4 mb-4">
    <div class="col-md-6">
        <div class="admin-stat-card">
            <span class="admin-stat-card__label">Categories</span>
            <strong class="admin-stat-card__value"><?= (int) $categoryCount; ?></strong>
            <a class="admin-stat-card__link" href="<?= e(url('admin/categories.php')); ?>">Manage categories</a>
        </div>
    </div>
    <div class="col-md-6">
        <div class="admin-stat-card">
            <span class="admin-stat-card__label">Slides</span>
            <strong class="admin-stat-card__value"><?= (int) $slideCount; ?></strong>
            <a class="admin-stat-card__link" href="<?= e(url('admin/slides.php')); ?>">Manage slides</a>
        </div>
    </div>
</div>

<div class="admin-card">
    <div class="admin-card__header">
        <div>
            <h2 class="admin-card__title mb-1">Recent Slides</h2>
            <p class="admin-card__copy mb-0">Latest entries created for the showcase component.</p>
        </div>
    </div>
    <div class="table-responsive">
        <table class="table admin-table align-middle mb-0">
            <thead>
            <tr>
                <th scope="col">Title</th>
                <th scope="col">Category</th>
                <th scope="col">Badge</th>
                <th scope="col">Status</th>
            </tr>
            </thead>
            <tbody>
            <?php if ($recentSlides === []): ?>
                <tr>
                    <td colspan="4" class="text-center py-4">No slides available yet.</td>
                </tr>
            <?php else: ?>
                <?php foreach ($recentSlides as $slide): ?>
                    <tr>
                        <td><?= e($slide['title']); ?></td>
                        <td><?= e($slide['category_title']); ?></td>
                        <td><?= e($slide['badge_text']); ?></td>
                        <td>
                            <span class="badge rounded-pill text-bg-<?= (int) $slide['status'] === 1 ? 'success' : 'secondary'; ?>">
                                <?= (int) $slide['status'] === 1 ? 'Active' : 'Inactive'; ?>
                            </span>
                        </td>
                    </tr>
                <?php endforeach; ?>
            <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>
