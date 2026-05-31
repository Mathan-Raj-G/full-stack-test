CREATE DATABASE IF NOT EXISTS `wpoets_full_stack`
    CHARACTER SET utf8mb4
    COLLATE utf8mb4_unicode_ci;

USE `wpoets_full_stack`;

DROP TABLE IF EXISTS `slides`;
DROP TABLE IF EXISTS `categories`;

CREATE TABLE `categories` (
    `id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
    `title` VARCHAR(120) NOT NULL,
    `icon` VARCHAR(255) NOT NULL,
    `status` TINYINT(1) NOT NULL DEFAULT 1,
    `created_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    `updated_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    PRIMARY KEY (`id`),
    KEY `idx_categories_status` (`status`),
    KEY `idx_categories_created_at` (`created_at`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE `slides` (
    `id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
    `category_id` INT UNSIGNED NOT NULL,
    `badge_text` VARCHAR(120) NOT NULL,
    `title` VARCHAR(180) NOT NULL,
    `description` VARCHAR(320) NOT NULL DEFAULT '',
    `button_text` VARCHAR(60) NOT NULL DEFAULT 'Learn More',
    `image` VARCHAR(255) NOT NULL,
    `sort_order` INT UNSIGNED NOT NULL DEFAULT 1,
    `status` TINYINT(1) NOT NULL DEFAULT 1,
    `created_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    `updated_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    PRIMARY KEY (`id`),
    KEY `idx_slides_category_status_sort` (`category_id`, `status`, `sort_order`),
    KEY `idx_slides_status` (`status`),
    CONSTRAINT `fk_slides_category`
        FOREIGN KEY (`category_id`) REFERENCES `categories` (`id`)
        ON DELETE CASCADE
        ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO `categories` (`id`, `title`, `icon`, `status`) VALUES
    (1, 'Learning', 'files/images/DL-learning.svg', 1),
    (2, 'Technology', 'files/images/DL-technology.svg', 1),
    (3, 'Communication', 'files/images/DL-communication.svg', 1);

INSERT INTO `slides` (`category_id`, `badge_text`, `title`, `description`, `button_text`, `image`, `sort_order`, `status`) VALUES
    (1, 'Digital Learning Infrastructure', 'Usability enhancement and Training for Transaction Portal for Customers', 'Learning-focused content card matching the supplied desktop and mobile reference states.', 'Learn More', 'files/images/DL-Technology.jpg', 1, 1),
    (1, 'Learning Experience Design', 'Blended onboarding modules for distributed teams and customer success workflows', 'Seed slide used to demonstrate per-category ordering and synchronized media updates.', 'Learn More', 'files/images/DL-Learning-1.jpg', 2, 1),
    (1, 'Capability Development', 'Scenario-based assessments that keep enterprise learners engaged across devices', 'Additional seed record to validate pagination, content switching, and mobile swipe behavior.', 'Learn More', 'files/images/DL-Communication.jpg', 3, 1),
    (2, 'Cloud Modernization', 'Operational dashboards and digital experiences that surface critical platform metrics', 'Technology slider seed content with responsive media support for the paired image column.', 'Learn More', 'files/images/DL-Technology.jpg', 1, 1),
    (2, 'Automation Enablement', 'Workflow acceleration through integrated systems, analytics, and smart content delivery', 'Supports testing of CRUD updates, status toggles, and image replacement in the admin panel.', 'Learn More', 'files/images/DL-Communication.jpg', 2, 1),
    (2, 'Enterprise Platforms', 'Scalable service architecture for high-volume learning and enablement programs', 'Seed slide ordered third in the Technology category for slider pagination and sequencing checks.', 'Learn More', 'files/images/DL-Learning-1.jpg', 3, 1),
    (3, 'Audience Engagement', 'Omnichannel communication design for campaigns, announcements, and outreach initiatives', 'Communication category content used for accordion and background-image rendering on mobile.', 'Learn More', 'files/images/DL-Communication.jpg', 1, 1),
    (3, 'Connected Messaging', 'Campaign assets and storytelling systems aligned to brand voice across channels', 'Demonstrates slide sorting, independent category grouping, and shared frontend rendering.', 'Learn More', 'files/images/DL-Learning-1.jpg', 2, 1),
    (3, 'Collaboration Journeys', 'Interactive media journeys that bring product, support, and stakeholder messaging together', 'Final seeded slide for validating full CRUD coverage, desktop tabs, and accordion transitions.', 'Learn More', 'files/images/DL-Technology.jpg', 3, 1);
