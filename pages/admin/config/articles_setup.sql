-- ============================================================
-- Articles & Blog Setup - Cantik.AI
-- ============================================================

-- Table: article_categories
CREATE TABLE IF NOT EXISTS `article_categories` (
  `id`          INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  `name`        VARCHAR(100) NOT NULL,
  `slug`        VARCHAR(100) NOT NULL UNIQUE,
  `description` TEXT DEFAULT NULL,
  `color`       VARCHAR(7) DEFAULT '#b84d7a',
  `sort_order`  INT NOT NULL DEFAULT 0,
  `is_active`   TINYINT(1) NOT NULL DEFAULT 1,
  `created_at`  DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at`  DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO `article_categories` (`name`, `slug`, `description`, `color`, `sort_order`) VALUES
('Regulasi & BPOM',    'regulasi-bpom',    'Informasi regulasi kosmetik dan BPOM Indonesia', '#b84d7a', 1),
('Formulasi & R&D',    'formulasi-rd',     'Riset dan pengembangan formulasi kosmetik',       '#e8a0bf', 2),
('Bisnis Kecantikan',  'bisnis-kecantikan','Strategi dan tips bisnis industri kecantikan',    '#9d5a76', 3),
('AI & Teknologi',     'ai-teknologi',     'Perkembangan AI di industri kecantikan',          '#6b4c5e', 4),
('Tips & Tutorial',    'tips-tutorial',    'Panduan praktis untuk pelaku industri',           '#b84d7a', 5);

-- Table: articles
CREATE TABLE IF NOT EXISTS `articles` (
  `id`                INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  `category_id`       INT UNSIGNED DEFAULT NULL,
  `author_id`         INT UNSIGNED NOT NULL,
  `title`             VARCHAR(255) NOT NULL,
  `slug`              VARCHAR(255) NOT NULL UNIQUE,
  `excerpt`           TEXT DEFAULT NULL COMMENT 'Short description for SEO & listing',
  `content`           LONGTEXT NOT NULL,
  `featured_image`    VARCHAR(500) DEFAULT NULL,
  `featured_image_alt`VARCHAR(255) DEFAULT NULL,
  -- SEO Fields
  `seo_title`         VARCHAR(70)  DEFAULT NULL COMMENT 'Max 70 chars for Google',
  `seo_description`   VARCHAR(160) DEFAULT NULL COMMENT 'Max 160 chars for Google',
  `seo_keywords`      VARCHAR(500) DEFAULT NULL,
  `canonical_url`     VARCHAR(500) DEFAULT NULL,
  `og_image`          VARCHAR(500) DEFAULT NULL COMMENT 'Open Graph image for social share',
  -- Status & Schedule
  `status`            ENUM('draft','scheduled','published','archived') NOT NULL DEFAULT 'draft',
  `scheduled_at`      DATETIME DEFAULT NULL COMMENT 'Publish at this time if status=scheduled',
  `published_at`      DATETIME DEFAULT NULL,
  -- Schema.org
  `schema_type`       ENUM('Article','BlogPosting','NewsArticle') NOT NULL DEFAULT 'BlogPosting',
  -- Tracking
  `view_count`        INT UNSIGNED NOT NULL DEFAULT 0,
  `read_time`         TINYINT UNSIGNED DEFAULT NULL COMMENT 'Estimated read time in minutes',
  `is_featured`       TINYINT(1) NOT NULL DEFAULT 0,
  `created_at`        DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at`        DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  CONSTRAINT `fk_article_category` FOREIGN KEY (`category_id`) REFERENCES `article_categories`(`id`) ON DELETE SET NULL,
  CONSTRAINT `fk_article_author`   FOREIGN KEY (`author_id`)   REFERENCES `admin_users`(`id`) ON DELETE RESTRICT,
  INDEX `idx_status`     (`status`),
  INDEX `idx_scheduled`  (`scheduled_at`),
  INDEX `idx_published`  (`published_at`),
  INDEX `idx_slug`       (`slug`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Table: article_tags
CREATE TABLE IF NOT EXISTS `article_tags` (
  `id`         INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  `name`       VARCHAR(100) NOT NULL,
  `slug`       VARCHAR(100) NOT NULL UNIQUE,
  `created_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Table: article_tag_pivot
CREATE TABLE IF NOT EXISTS `article_tag_pivot` (
  `article_id` INT UNSIGNED NOT NULL,
  `tag_id`     INT UNSIGNED NOT NULL,
  PRIMARY KEY (`article_id`, `tag_id`),
  CONSTRAINT `fk_pivot_article` FOREIGN KEY (`article_id`) REFERENCES `articles`(`id`) ON DELETE CASCADE,
  CONSTRAINT `fk_pivot_tag`     FOREIGN KEY (`tag_id`)     REFERENCES `article_tags`(`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
