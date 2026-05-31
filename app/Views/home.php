<?php
/** @var array<int, array<string, mixed>> $categories */
/** @var int|null $activeCategoryId */
?>
<section class="showcase-section">
    <div class="container-xl">
        <div class="showcase-intro text-center">
            <h1 class="showcase-intro__title"><?= e($heroTitle); ?></h1>
            <p class="showcase-intro__subtitle mb-0"><?= e($heroSubtitle); ?></p>
        </div>

        <?php if ($categories === []): ?>
            <div class="showcase-empty text-center" role="status">
                <h2 class="showcase-empty__title">No active slider content yet</h2>
                <p class="showcase-empty__copy mb-0">Add categories and slides from the admin panel to populate the showcase.</p>
                <a class="btn showcase-empty__button" href="<?= e(url('admin/index.php')); ?>">Open Admin Panel</a>
            </div>
        <?php else: ?>
            <div class="showcase-desktop d-none d-lg-block">
                <div class="showcase-shell row g-0">
                    <div class="col-12 col-lg-3 showcase-shell__tabs-column">
                        <div class="showcase-tabs" role="tablist" aria-label="Content categories">
                            <?php foreach ($categories as $index => $category): ?>
                                <?php $isActive = (int) $category['id'] === (int) $activeCategoryId; ?>
                                <button
                                    class="showcase-tab<?= $isActive ? ' is-active' : ''; ?>"
                                    id="desktop-tab-<?= (int) $category['id']; ?>"
                                    type="button"
                                    role="tab"
                                    aria-selected="<?= $isActive ? 'true' : 'false'; ?>"
                                    aria-controls="desktop-panel-<?= (int) $category['id']; ?>"
                                    tabindex="<?= $isActive ? '0' : '-1'; ?>"
                                    data-category-tab
                                    data-category-id="<?= (int) $category['id']; ?>"
                                >
                                    <span class="showcase-tab__icon" aria-hidden="true">
                                        <img src="<?= e(asset($category['icon'])); ?>" alt="" loading="lazy">
                                    </span>
                                    <span class="showcase-tab__label"><?= e($category['title']); ?></span>
                                </button>
                            <?php endforeach; ?>
                        </div>
                    </div>
                    <div class="col-12 col-lg-9 showcase-shell__panes-column">
                        <?php foreach ($categories as $category): ?>
                            <?php $isActive = (int) $category['id'] === (int) $activeCategoryId; ?>
                            <div
                                class="showcase-pane<?= $isActive ? ' is-active' : ''; ?>"
                                id="desktop-panel-<?= (int) $category['id']; ?>"
                                role="tabpanel"
                                aria-labelledby="desktop-tab-<?= (int) $category['id']; ?>"
                                tabindex="0"
                                data-category-pane
                                data-category-id="<?= (int) $category['id']; ?>"
                            >
                                <div class="showcase-pane__content">
                                    <div class="swiper showcase-content-swiper" data-content-swiper>
                                        <div class="swiper-wrapper">
                                            <?php foreach ($category['slides'] as $slide): ?>
                                                <article class="swiper-slide showcase-card" aria-label="<?= e($slide['title']); ?>">
                                                    <div class="showcase-card__body">
                                                        <span class="showcase-card__badge"><?= e($slide['badge_text']); ?></span>
                                                        <h2 class="showcase-card__title"><?= e($slide['title']); ?></h2>
                                                        <?php if ($slide['description'] !== ''): ?>
                                                            <p class="showcase-card__description visually-hidden"><?= e($slide['description']); ?></p>
                                                        <?php endif; ?>
                                                        <button class="showcase-card__cta" type="button" aria-label="<?= e($slide['button_text'] . ': ' . $slide['title']); ?>">
                                                            <span><?= e($slide['button_text']); ?></span>
                                                            <img src="<?= e(asset('files/images/arrow-right.svg')); ?>" alt="" aria-hidden="true">
                                                        </button>
                                                    </div>
                                                </article>
                                            <?php endforeach; ?>
                                        </div>
                                        <div class="showcase-content-swiper__pagination" data-content-pagination></div>
                                    </div>
                                </div>
                                <div class="showcase-pane__media">
                                    <div class="swiper showcase-image-swiper" data-image-swiper>
                                        <div class="swiper-wrapper">
                                            <?php foreach ($category['slides'] as $slide): ?>
                                                <div class="swiper-slide showcase-image-slide">
                                                    <img
                                                        src="<?= e(asset($slide['image'])); ?>"
                                                        alt="<?= e($slide['title']); ?>"
                                                        loading="lazy"
                                                        width="660"
                                                        height="660"
                                                    >
                                                </div>
                                            <?php endforeach; ?>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>
            </div>

            <div class="showcase-mobile d-lg-none">
                <div class="mobile-accordion" data-mobile-accordion>
                    <?php foreach ($categories as $category): ?>
                        <?php $isActive = (int) $category['id'] === (int) $activeCategoryId; ?>
                        <section class="mobile-accordion__item<?= $isActive ? ' is-open' : ''; ?>">
                            <h2 class="mobile-accordion__heading">
                                <button
                                    class="mobile-accordion__trigger"
                                    type="button"
                                    aria-expanded="<?= $isActive ? 'true' : 'false'; ?>"
                                    aria-controls="mobile-panel-<?= (int) $category['id']; ?>"
                                    data-accordion-trigger
                                >
                                    <span class="mobile-accordion__title-group">
                                        <span class="mobile-accordion__icon" aria-hidden="true">
                                            <img src="<?= e(asset($category['icon'])); ?>" alt="" loading="lazy">
                                        </span>
                                        <span class="mobile-accordion__title"><?= e($category['title']); ?></span>
                                    </span>
                                    <span class="mobile-accordion__indicator" aria-hidden="true">
                                        <img class="mobile-accordion__indicator-icon mobile-accordion__indicator-icon--plus" src="<?= e(asset('files/images/plus-01.svg')); ?>" alt="">
                                        <img class="mobile-accordion__indicator-icon mobile-accordion__indicator-icon--minus" src="<?= e(asset('files/images/minus-01.svg')); ?>" alt="">
                                    </span>
                                </button>
                            </h2>
                            <div
                                class="mobile-accordion__panel"
                                id="mobile-panel-<?= (int) $category['id']; ?>"
                                <?= $isActive ? '' : 'hidden'; ?>
                                data-accordion-panel
                            >
                                <div class="mobile-accordion__panel-inner">
                                    <div class="swiper mobile-showcase-swiper" data-mobile-swiper>
                                        <div class="swiper-wrapper">
                                            <?php foreach ($category['slides'] as $slide): ?>
                                                <article class="swiper-slide mobile-showcase-slide">
                                                    <img
                                                        class="mobile-showcase-slide__background"
                                                        src="<?= e(asset($slide['image'])); ?>"
                                                        alt=""
                                                        loading="lazy"
                                                        aria-hidden="true"
                                                    >
                                                    <div class="mobile-showcase-slide__overlay"></div>
                                                    <div class="mobile-showcase-slide__content">
                                                        <span class="mobile-showcase-slide__badge"><?= e($slide['badge_text']); ?></span>
                                                        <h3 class="mobile-showcase-slide__title"><?= e($slide['title']); ?></h3>
                                                        <?php if ($slide['description'] !== ''): ?>
                                                            <p class="visually-hidden"><?= e($slide['description']); ?></p>
                                                        <?php endif; ?>
                                                        <button class="mobile-showcase-slide__cta" type="button" aria-label="<?= e($slide['button_text'] . ': ' . $slide['title']); ?>">
                                                            <span><?= e($slide['button_text']); ?></span>
                                                            <img src="<?= e(asset('files/images/arrow-right.svg')); ?>" alt="" aria-hidden="true">
                                                        </button>
                                                    </div>
                                                </article>
                                            <?php endforeach; ?>
                                        </div>
                                        <div class="mobile-showcase-swiper__pagination" data-mobile-pagination></div>
                                    </div>
                                </div>
                            </div>
                        </section>
                    <?php endforeach; ?>
                </div>
            </div>
        <?php endif; ?>
    </div>
</section>
