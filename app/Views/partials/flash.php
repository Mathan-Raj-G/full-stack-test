<?php $status = flash('status'); ?>
<?php if ($status): ?>
    <div class="alert alert-<?= e($status['type'] ?? 'info'); ?> alert-dismissible fade show mb-4" role="alert">
        <?= e($status['message'] ?? ''); ?>
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
<?php endif; ?>
