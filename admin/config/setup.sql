-- ============================================================
-- Cantik AI Database Setup
-- Database: cantikai-db
-- ============================================================

CREATE DATABASE IF NOT EXISTS `cantikai-db`
  CHARACTER SET utf8mb4
  COLLATE utf8mb4_unicode_ci;

USE `cantikai-db`;

-- ============================================================
-- Table: admin_users
-- ============================================================
CREATE TABLE IF NOT EXISTS `admin_users` (
  `id`         INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  `username`   VARCHAR(100) NOT NULL UNIQUE,
  `email`      VARCHAR(150) NOT NULL UNIQUE,
  `password`   VARCHAR(255) NOT NULL,
  `full_name`  VARCHAR(150) NOT NULL,
  `role`       ENUM('superadmin','admin','editor') NOT NULL DEFAULT 'editor',
  `avatar`     VARCHAR(255) DEFAULT NULL,
  `is_active`  TINYINT(1) NOT NULL DEFAULT 1,
  `last_login` DATETIME DEFAULT NULL,
  `created_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Default admin user: admin / password
INSERT INTO `admin_users` (`username`, `email`, `password`, `full_name`, `role`) VALUES
('it.rayandra@gmail.com', 'it.rayandra@gmail.com', '$2y$12$vPXFuYHeIDTjpBX0DzniteZmCTYrbvpwIsyNlwAoq5Udv0u5ta02.', 'Super Admin', 'superadmin');
-- Password hash above = '123' (bcrypt). Change after first login!

-- ============================================================
-- Table: nav_menus  (header menu groups)
-- ============================================================
CREATE TABLE IF NOT EXISTS `nav_menus` (
  `id`          INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  `name`        VARCHAR(100) NOT NULL COMMENT 'Internal name, e.g. Main Navigation',
  `slug`        VARCHAR(100) NOT NULL UNIQUE,
  `description` VARCHAR(255) DEFAULT NULL,
  `is_active`   TINYINT(1) NOT NULL DEFAULT 1,
  `created_at`  DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at`  DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO `nav_menus` (`name`, `slug`, `description`) VALUES
('Main Navigation', 'main-nav', 'Header navigation menu utama');

-- ============================================================
-- Table: nav_menu_items  (menu items + submenu)
-- ============================================================
CREATE TABLE IF NOT EXISTS `nav_menu_items` (
  `id`          INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  `menu_id`     INT UNSIGNED NOT NULL,
  `parent_id`   INT UNSIGNED DEFAULT NULL COMMENT 'NULL = top-level, set = submenu item',
  `title`       VARCHAR(150) NOT NULL,
  `url`         VARCHAR(500) NOT NULL DEFAULT '#',
  `target`      ENUM('_self','_blank') NOT NULL DEFAULT '_self',
  `icon_image`  VARCHAR(500) DEFAULT NULL COMMENT 'Path to icon/image shown before title',
  `icon_alt`    VARCHAR(150) DEFAULT NULL,
  `css_class`   VARCHAR(150) DEFAULT NULL,
  `sort_order`  INT NOT NULL DEFAULT 0,
  `is_active`   TINYINT(1) NOT NULL DEFAULT 1,
  `created_at`  DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at`  DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  CONSTRAINT `fk_item_menu`   FOREIGN KEY (`menu_id`)   REFERENCES `nav_menus`(`id`) ON DELETE CASCADE,
  CONSTRAINT `fk_item_parent` FOREIGN KEY (`parent_id`) REFERENCES `nav_menu_items`(`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Seed: sample menu items
INSERT INTO `nav_menu_items` (`menu_id`, `parent_id`, `title`, `url`, `sort_order`) VALUES
(1, NULL, 'Beranda',  '/',          1),
(1, NULL, 'Fitur',    '/fitur',     2),
(1, NULL, 'Harga',    '/pricing',   3),
(1, NULL, 'Blog',     '/blog',      4),
(1, NULL, 'Tentang',  '/tentang',   5);

-- Submenu under Fitur (id=2)
INSERT INTO `nav_menu_items` (`menu_id`, `parent_id`, `title`, `url`, `sort_order`) VALUES
(1, 2, 'AI Assistant', '/fitur/ai-assistant', 1),
(1, 2, 'Otomatisasi',  '/fitur/otomatisasi',  2),
(1, 2, 'Integrasi',    '/fitur/integrasi',    3);

-- ============================================================
-- Table: activity_log
-- ============================================================
CREATE TABLE IF NOT EXISTS `activity_log` (
  `id`          INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  `user_id`     INT UNSIGNED DEFAULT NULL,
  `action`      VARCHAR(100) NOT NULL,
  `description` TEXT DEFAULT NULL,
  `ip_address`  VARCHAR(45) DEFAULT NULL,
  `created_at`  DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  CONSTRAINT `fk_log_user` FOREIGN KEY (`user_id`) REFERENCES `admin_users`(`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
