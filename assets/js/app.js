(function ($) {
    "use strict";

    const desktopBreakpoint = window.matchMedia("(min-width: 992px)");

    function createDesktopSwiper($pane) {
        if ($pane.data("desktopReady")) {
            return;
        }

        const contentElement = $pane.find("[data-content-swiper]").get(0);
        const imageElement = $pane.find("[data-image-swiper]").get(0);
        const paginationElement = $pane.find("[data-content-pagination]").get(0);

        if (!contentElement || !imageElement || typeof Swiper === "undefined") {
            return;
        }

        const imageSwiper = new Swiper(imageElement, {
            effect: "fade",
            fadeEffect: {
                crossFade: true
            },
            speed: 650,
            allowTouchMove: false,
            simulateTouch: false
        });

        const contentSwiper = new Swiper(contentElement, {
            effect: "fade",
            fadeEffect: {
                crossFade: true
            },
            speed: 650,
            autoHeight: false,
            keyboard: {
                enabled: true,
                onlyInViewport: false
            },
            pagination: {
                el: paginationElement,
                clickable: true
            }
        });

        contentSwiper.controller.control = imageSwiper;
        imageSwiper.controller.control = contentSwiper;

        $pane.data("desktopReady", true);
        $pane.data("contentSwiper", contentSwiper);
        $pane.data("imageSwiper", imageSwiper);
    }

    function activateDesktopCategory(categoryId, moveFocus) {
        const $tabs = $("[data-category-tab]");
        const $panes = $("[data-category-pane]");
        const $activeTab = $tabs.filter('[data-category-id="' + categoryId + '"]');
        const $activePane = $panes.filter('[data-category-id="' + categoryId + '"]');

        if ($activeTab.length === 0 || $activePane.length === 0) {
            return;
        }

        $tabs.each(function () {
            const $tab = $(this);
            const isActive = String($tab.data("categoryId")) === String(categoryId);

            $tab.toggleClass("is-active", isActive);
            $tab.attr("aria-selected", isActive ? "true" : "false");
            $tab.attr("tabindex", isActive ? "0" : "-1");
        });

        $panes.removeClass("is-active");
        $activePane.addClass("is-active");

        createDesktopSwiper($activePane);

        const contentSwiper = $activePane.data("contentSwiper");
        const imageSwiper = $activePane.data("imageSwiper");

        if (contentSwiper) {
            contentSwiper.update();
        }

        if (imageSwiper) {
            imageSwiper.update();
        }

        if (moveFocus) {
            $activeTab.trigger("focus");
        }
    }

    function bindDesktopTabs() {
        $(document).on("click", "[data-category-tab]", function () {
            activateDesktopCategory($(this).data("categoryId"), false);
        });

        $(document).on("keydown", "[data-category-tab]", function (event) {
            const $tabs = $("[data-category-tab]");
            const currentIndex = $tabs.index(this);
            let nextIndex = currentIndex;

            switch (event.key) {
                case "ArrowDown":
                case "ArrowRight":
                    nextIndex = (currentIndex + 1) % $tabs.length;
                    break;
                case "ArrowUp":
                case "ArrowLeft":
                    nextIndex = (currentIndex - 1 + $tabs.length) % $tabs.length;
                    break;
                case "Home":
                    nextIndex = 0;
                    break;
                case "End":
                    nextIndex = $tabs.length - 1;
                    break;
                default:
                    return;
            }

            event.preventDefault();
            activateDesktopCategory($tabs.eq(nextIndex).data("categoryId"), true);
        });
    }

    function createMobileSwiper($panel) {
        const $swiper = $panel.find("[data-mobile-swiper]").first();

        if ($swiper.length === 0 || $swiper.data("mobileReady") || typeof Swiper === "undefined") {
            return;
        }

        const swiperInstance = new Swiper($swiper.get(0), {
            slidesPerView: 1,
            speed: 600,
            grabCursor: true,
            keyboard: {
                enabled: true,
                onlyInViewport: false
            },
            pagination: {
                el: $panel.find("[data-mobile-pagination]").get(0),
                clickable: true
            }
        });

        $swiper.data("mobileReady", true);
        $swiper.data("mobileSwiper", swiperInstance);
    }

    function setAccordionState($item, shouldOpen) {
        const $trigger = $item.find("[data-accordion-trigger]").first();
        const $panel = $item.find("[data-accordion-panel]").first();

        if (shouldOpen) {
            $item.addClass("is-open");
            $trigger.attr("aria-expanded", "true");
            $panel.removeAttr("hidden").stop(true, true).slideDown(260, function () {
                createMobileSwiper($panel);
                const $swiper = $panel.find("[data-mobile-swiper]").first();
                const swiperInstance = $swiper.data("mobileSwiper");

                if (swiperInstance) {
                    swiperInstance.update();
                }
            });

            return;
        }

        $item.removeClass("is-open");
        $trigger.attr("aria-expanded", "false");
        $panel.stop(true, true).slideUp(220, function () {
            $panel.attr("hidden", "hidden");
        });
    }

    function bindMobileAccordion() {
        $("[data-accordion-panel][hidden]").css("display", "none");
        $(".mobile-accordion__item.is-open").each(function () {
            createMobileSwiper($(this).find("[data-accordion-panel]").first());
        });

        $(document).on("click", "[data-accordion-trigger]", function () {
            const $item = $(this).closest(".mobile-accordion__item");
            const isOpen = $item.hasClass("is-open");

            $(".mobile-accordion__item").not($item).each(function () {
                setAccordionState($(this), false);
            });

            setAccordionState($item, !isOpen);
        });
    }

    function bootstrapShowcase() {
        const activeDesktopId = $(".showcase-tab.is-active").data("categoryId");

        if (activeDesktopId !== undefined) {
            activateDesktopCategory(activeDesktopId, false);
        }

        bindDesktopTabs();
        bindMobileAccordion();
    }

    $(function () {
        bootstrapShowcase();

        desktopBreakpoint.addEventListener("change", function () {
            const activeDesktopId = $(".showcase-tab.is-active").data("categoryId");

            if (activeDesktopId !== undefined && desktopBreakpoint.matches) {
                activateDesktopCategory(activeDesktopId, false);
            }

            $(".mobile-accordion__item.is-open").each(function () {
                const $panel = $(this).find("[data-accordion-panel]").first();
                createMobileSwiper($panel);
            });
        });
    });
}(jQuery));
