-- =====================================================================
-- AMÉLIE — Esquema de base de datos
-- MySQL 5.7+ / MariaDB 10.3+
-- =====================================================================

SET NAMES utf8mb4;
SET FOREIGN_KEY_CHECKS = 0;

-- ---------------------------------------------------------------------
-- Sistema y configuración
-- ---------------------------------------------------------------------

DROP TABLE IF EXISTS `languages`;
CREATE TABLE `languages` (
  `code`        CHAR(2)      NOT NULL,
  `name`        VARCHAR(50)  NOT NULL,
  `native_name` VARCHAR(50)  NOT NULL,
  `is_default`  TINYINT(1)   NOT NULL DEFAULT 0,
  `is_active`   TINYINT(1)   NOT NULL DEFAULT 1,
  `position`    INT          NOT NULL DEFAULT 0,
  PRIMARY KEY (`code`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

DROP TABLE IF EXISTS `settings`;
CREATE TABLE `settings` (
  `skey`   VARCHAR(100) NOT NULL,
  `svalue` TEXT         NULL,
  `sgroup` VARCHAR(50)  NOT NULL DEFAULT 'general',
  PRIMARY KEY (`skey`),
  KEY `idx_settings_group` (`sgroup`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

DROP TABLE IF EXISTS `ui_strings`;
CREATE TABLE `ui_strings` (
  `id`    INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `lang`  CHAR(2)      NOT NULL,
  `skey`  VARCHAR(150) NOT NULL,
  `value` TEXT         NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `uq_ui_strings` (`lang`,`skey`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

DROP TABLE IF EXISTS `themes`;
CREATE TABLE `themes` (
  `id`          INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `tkey`        VARCHAR(50)  NOT NULL,
  `name`        VARCHAR(80)  NOT NULL,
  `description` VARCHAR(255) NULL,
  `swatch`      VARCHAR(120) NOT NULL DEFAULT '',
  `is_active`   TINYINT(1)   NOT NULL DEFAULT 0,
  `position`    INT          NOT NULL DEFAULT 0,
  PRIMARY KEY (`id`),
  UNIQUE KEY `uq_themes_key` (`tkey`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

DROP TABLE IF EXISTS `users`;
CREATE TABLE `users` (
  `id`              INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `name`            VARCHAR(120) NOT NULL,
  `email`           VARCHAR(190) NOT NULL,
  `password`        VARCHAR(255) NOT NULL,
  `role`            ENUM('admin','editor') NOT NULL DEFAULT 'editor',
  `is_active`       TINYINT(1)   NOT NULL DEFAULT 1,
  `failed_attempts` INT          NOT NULL DEFAULT 0,
  `locked_until`    DATETIME     NULL,
  `last_login_at`   DATETIME     NULL,
  `created_at`      DATETIME     NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `uq_users_email` (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

DROP TABLE IF EXISTS `media`;
CREATE TABLE `media` (
  `id`         INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `filename`   VARCHAR(190) NOT NULL,
  `path`       VARCHAR(255) NOT NULL,
  `mime`       VARCHAR(80)  NOT NULL,
  `size`       INT UNSIGNED NOT NULL DEFAULT 0,
  `width`      INT UNSIGNED NOT NULL DEFAULT 0,
  `height`     INT UNSIGNED NOT NULL DEFAULT 0,
  `alt`        VARCHAR(255) NOT NULL DEFAULT '',
  `created_at` DATETIME     NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

DROP TABLE IF EXISTS `activity_log`;
CREATE TABLE `activity_log` (
  `id`          INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `user_id`     INT UNSIGNED NULL,
  `user_name`   VARCHAR(120) NOT NULL DEFAULT '',
  `action`      VARCHAR(80)  NOT NULL,
  `entity`      VARCHAR(80)  NOT NULL DEFAULT '',
  `entity_id`   INT UNSIGNED NULL,
  `description` VARCHAR(255) NOT NULL DEFAULT '',
  `created_at`  DATETIME     NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `idx_log_created` (`created_at`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ---------------------------------------------------------------------
-- Catálogo
-- ---------------------------------------------------------------------

DROP TABLE IF EXISTS `categories`;
CREATE TABLE `categories` (
  `id`        INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `slug`      VARCHAR(120) NOT NULL,
  `image`     VARCHAR(255) NOT NULL DEFAULT '',
  `position`  INT          NOT NULL DEFAULT 0,
  `is_active` TINYINT(1)   NOT NULL DEFAULT 1,
  PRIMARY KEY (`id`),
  UNIQUE KEY `uq_categories_slug` (`slug`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

DROP TABLE IF EXISTS `category_translations`;
CREATE TABLE `category_translations` (
  `id`               INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `category_id`      INT UNSIGNED NOT NULL,
  `lang`             CHAR(2)      NOT NULL,
  `name`             VARCHAR(160) NOT NULL,
  `description`      TEXT         NULL,
  `meta_title`       VARCHAR(190) NULL,
  `meta_description` VARCHAR(255) NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `uq_cat_tr` (`category_id`,`lang`),
  CONSTRAINT `fk_cat_tr` FOREIGN KEY (`category_id`) REFERENCES `categories`(`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

DROP TABLE IF EXISTS `products`;
CREATE TABLE `products` (
  `id`            INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `slug`          VARCHAR(140) NOT NULL,
  `sku`           VARCHAR(60)  NOT NULL DEFAULT '',
  `category_id`   INT UNSIGNED NULL,
  `price`         DECIMAL(10,2) NOT NULL DEFAULT 0.00,
  `compare_price` DECIMAL(10,2) NULL,
  `status`        ENUM('draft','published','archived') NOT NULL DEFAULT 'draft',
  `is_featured`   TINYINT(1)   NOT NULL DEFAULT 0,
  `size_mode`     ENUM('single','bra_brief') NOT NULL DEFAULT 'single',
  `position`      INT          NOT NULL DEFAULT 0,
  `created_at`    DATETIME     NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at`    DATETIME     NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `uq_products_slug` (`slug`),
  KEY `idx_products_status` (`status`),
  KEY `idx_products_category` (`category_id`),
  CONSTRAINT `fk_products_category` FOREIGN KEY (`category_id`) REFERENCES `categories`(`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

DROP TABLE IF EXISTS `product_translations`;
CREATE TABLE `product_translations` (
  `id`                INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `product_id`        INT UNSIGNED NOT NULL,
  `lang`              CHAR(2)      NOT NULL,
  `name`              VARCHAR(190) NOT NULL,
  `tagline`           VARCHAR(255) NULL,
  `short_description` TEXT         NULL,
  `description`       TEXT         NULL,
  `composition`       TEXT         NULL,
  `care`              TEXT         NULL,
  `shipping_returns`  TEXT         NULL,
  `meta_title`        VARCHAR(190) NULL,
  `meta_description`  VARCHAR(255) NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `uq_prod_tr` (`product_id`,`lang`),
  CONSTRAINT `fk_prod_tr` FOREIGN KEY (`product_id`) REFERENCES `products`(`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

DROP TABLE IF EXISTS `product_images`;
CREATE TABLE `product_images` (
  `id`         INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `product_id` INT UNSIGNED NOT NULL,
  `path`       VARCHAR(255) NOT NULL,
  `alt`        VARCHAR(255) NOT NULL DEFAULT '',
  `kind`       ENUM('model','product','detail','packaging') NOT NULL DEFAULT 'product',
  `position`   INT          NOT NULL DEFAULT 0,
  `is_primary` TINYINT(1)   NOT NULL DEFAULT 0,
  PRIMARY KEY (`id`),
  KEY `idx_img_product` (`product_id`,`position`),
  CONSTRAINT `fk_img_product` FOREIGN KEY (`product_id`) REFERENCES `products`(`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- size      -> talla única (The Muse)
-- size_bra  -> talla de sujetador (Blush Floral)
-- size_brief-> talla de tanga     (Blush Floral)
DROP TABLE IF EXISTS `product_variants`;
CREATE TABLE `product_variants` (
  `id`             INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `product_id`     INT UNSIGNED NOT NULL,
  `sku`            VARCHAR(60)  NOT NULL DEFAULT '',
  `size`           VARCHAR(16)  NULL,
  `size_bra`       VARCHAR(16)  NULL,
  `size_brief`     VARCHAR(16)  NULL,
  `price_override` DECIMAL(10,2) NULL,
  `stock`          INT          NOT NULL DEFAULT 0,
  `is_active`      TINYINT(1)   NOT NULL DEFAULT 1,
  `position`       INT          NOT NULL DEFAULT 0,
  PRIMARY KEY (`id`),
  KEY `idx_var_product` (`product_id`),
  CONSTRAINT `fk_var_product` FOREIGN KEY (`product_id`) REFERENCES `products`(`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

DROP TABLE IF EXISTS `size_guides`;
CREATE TABLE `size_guides` (
  `id`       INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `gkey`     VARCHAR(60)  NOT NULL,
  `position` INT          NOT NULL DEFAULT 0,
  PRIMARY KEY (`id`),
  UNIQUE KEY `uq_guide_key` (`gkey`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

DROP TABLE IF EXISTS `size_guide_rows`;
CREATE TABLE `size_guide_rows` (
  `id`        INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `guide_id`  INT UNSIGNED NOT NULL,
  `label`     VARCHAR(40)  NOT NULL,
  `bust`      VARCHAR(40)  NOT NULL DEFAULT '',
  `underbust` VARCHAR(40)  NOT NULL DEFAULT '',
  `waist`     VARCHAR(40)  NOT NULL DEFAULT '',
  `hip`       VARCHAR(40)  NOT NULL DEFAULT '',
  `eu`        VARCHAR(20)  NOT NULL DEFAULT '',
  `it`        VARCHAR(20)  NOT NULL DEFAULT '',
  `uk`        VARCHAR(20)  NOT NULL DEFAULT '',
  `us`        VARCHAR(20)  NOT NULL DEFAULT '',
  `position`  INT          NOT NULL DEFAULT 0,
  PRIMARY KEY (`id`),
  KEY `idx_row_guide` (`guide_id`,`position`),
  CONSTRAINT `fk_row_guide` FOREIGN KEY (`guide_id`) REFERENCES `size_guides`(`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ---------------------------------------------------------------------
-- Contenido editorial
-- ---------------------------------------------------------------------

DROP TABLE IF EXISTS `pages`;
CREATE TABLE `pages` (
  `id`        INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `slug`      VARCHAR(120) NOT NULL,
  `template`  VARCHAR(50)  NOT NULL DEFAULT 'default',
  `is_system` TINYINT(1)   NOT NULL DEFAULT 0,
  `is_active` TINYINT(1)   NOT NULL DEFAULT 1,
  `position`  INT          NOT NULL DEFAULT 0,
  PRIMARY KEY (`id`),
  UNIQUE KEY `uq_pages_slug` (`slug`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

DROP TABLE IF EXISTS `page_translations`;
CREATE TABLE `page_translations` (
  `id`               INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `page_id`          INT UNSIGNED NOT NULL,
  `lang`             CHAR(2)      NOT NULL,
  `title`            VARCHAR(190) NOT NULL,
  `intro`            TEXT         NULL,
  `body`             MEDIUMTEXT   NULL,
  `meta_title`       VARCHAR(190) NULL,
  `meta_description` VARCHAR(255) NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `uq_page_tr` (`page_id`,`lang`),
  CONSTRAINT `fk_page_tr` FOREIGN KEY (`page_id`) REFERENCES `pages`(`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

DROP TABLE IF EXISTS `posts`;
CREATE TABLE `posts` (
  `id`           INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `slug`         VARCHAR(140) NOT NULL,
  `cover_image`  VARCHAR(255) NOT NULL DEFAULT '',
  `author_id`    INT UNSIGNED NULL,
  `status`       ENUM('draft','published') NOT NULL DEFAULT 'draft',
  `published_at` DATETIME     NULL,
  `created_at`   DATETIME     NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at`   DATETIME     NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `uq_posts_slug` (`slug`),
  KEY `idx_posts_status` (`status`,`published_at`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

DROP TABLE IF EXISTS `post_translations`;
CREATE TABLE `post_translations` (
  `id`               INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `post_id`          INT UNSIGNED NOT NULL,
  `lang`             CHAR(2)      NOT NULL,
  `title`            VARCHAR(190) NOT NULL,
  `excerpt`          TEXT         NULL,
  `body`             MEDIUMTEXT   NULL,
  `meta_title`       VARCHAR(190) NULL,
  `meta_description` VARCHAR(255) NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `uq_post_tr` (`post_id`,`lang`),
  CONSTRAINT `fk_post_tr` FOREIGN KEY (`post_id`) REFERENCES `posts`(`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

DROP TABLE IF EXISTS `faqs`;
CREATE TABLE `faqs` (
  `id`        INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `position`  INT          NOT NULL DEFAULT 0,
  `is_active` TINYINT(1)   NOT NULL DEFAULT 1,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

DROP TABLE IF EXISTS `faq_translations`;
CREATE TABLE `faq_translations` (
  `id`       INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `faq_id`   INT UNSIGNED NOT NULL,
  `lang`     CHAR(2)      NOT NULL,
  `question` VARCHAR(255) NOT NULL,
  `answer`   TEXT         NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `uq_faq_tr` (`faq_id`,`lang`),
  CONSTRAINT `fk_faq_tr` FOREIGN KEY (`faq_id`) REFERENCES `faqs`(`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ---------------------------------------------------------------------
-- Venta
-- ---------------------------------------------------------------------

DROP TABLE IF EXISTS `coupons`;
CREATE TABLE `coupons` (
  `id`          INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `code`        VARCHAR(40)  NOT NULL,
  `type`        ENUM('percent','fixed','free_shipping') NOT NULL DEFAULT 'percent',
  `value`       DECIMAL(10,2) NOT NULL DEFAULT 0.00,
  `min_amount`  DECIMAL(10,2) NOT NULL DEFAULT 0.00,
  `starts_at`   DATE         NULL,
  `ends_at`     DATE         NULL,
  `usage_limit` INT          NULL,
  `used_count`  INT          NOT NULL DEFAULT 0,
  `is_active`   TINYINT(1)   NOT NULL DEFAULT 1,
  PRIMARY KEY (`id`),
  UNIQUE KEY `uq_coupons_code` (`code`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

DROP TABLE IF EXISTS `shipping_rates`;
CREATE TABLE `shipping_rates` (
  `id`            INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `zone_name`     VARCHAR(120) NOT NULL,
  `countries`     TEXT         NOT NULL,
  `price`         DECIMAL(10,2) NOT NULL DEFAULT 0.00,
  `free_from`     DECIMAL(10,2) NULL,
  `delivery_days` VARCHAR(40)  NOT NULL DEFAULT '',
  `is_active`     TINYINT(1)   NOT NULL DEFAULT 1,
  `position`      INT          NOT NULL DEFAULT 0,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

DROP TABLE IF EXISTS `orders`;
CREATE TABLE `orders` (
  `id`                INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `order_number`      VARCHAR(30)  NOT NULL,
  `lang`              CHAR(2)      NOT NULL DEFAULT 'es',
  `status`            ENUM('pending','paid','processing','shipped','delivered','cancelled','refunded') NOT NULL DEFAULT 'pending',
  `payment_method`    VARCHAR(40)  NOT NULL DEFAULT 'bank_transfer',
  `payment_status`    ENUM('pending','paid','failed','refunded') NOT NULL DEFAULT 'pending',
  `payment_reference` VARCHAR(190) NOT NULL DEFAULT '',
  `customer_name`     VARCHAR(160) NOT NULL,
  `customer_email`    VARCHAR(190) NOT NULL,
  `customer_phone`    VARCHAR(40)  NOT NULL DEFAULT '',
  `ship_address`      VARCHAR(255) NOT NULL,
  `ship_address2`     VARCHAR(255) NOT NULL DEFAULT '',
  `ship_city`         VARCHAR(120) NOT NULL,
  `ship_postal`       VARCHAR(20)  NOT NULL,
  `ship_province`     VARCHAR(120) NOT NULL DEFAULT '',
  `ship_country`      CHAR(2)      NOT NULL DEFAULT 'ES',
  `subtotal`          DECIMAL(10,2) NOT NULL DEFAULT 0.00,
  `shipping_cost`     DECIMAL(10,2) NOT NULL DEFAULT 0.00,
  `discount_total`    DECIMAL(10,2) NOT NULL DEFAULT 0.00,
  `total`             DECIMAL(10,2) NOT NULL DEFAULT 0.00,
  `currency`          CHAR(3)      NOT NULL DEFAULT 'EUR',
  `coupon_code`       VARCHAR(40)  NOT NULL DEFAULT '',
  `notes`             TEXT         NULL,
  `admin_notes`       TEXT         NULL,
  `tracking_code`     VARCHAR(120) NOT NULL DEFAULT '',
  `created_at`        DATETIME     NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at`        DATETIME     NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `uq_orders_number` (`order_number`),
  KEY `idx_orders_status` (`status`),
  KEY `idx_orders_created` (`created_at`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

DROP TABLE IF EXISTS `order_items`;
CREATE TABLE `order_items` (
  `id`            INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `order_id`      INT UNSIGNED NOT NULL,
  `product_id`    INT UNSIGNED NULL,
  `variant_id`    INT UNSIGNED NULL,
  `product_name`  VARCHAR(190) NOT NULL,
  `variant_label` VARCHAR(120) NOT NULL DEFAULT '',
  `sku`           VARCHAR(60)  NOT NULL DEFAULT '',
  `image`         VARCHAR(255) NOT NULL DEFAULT '',
  `unit_price`    DECIMAL(10,2) NOT NULL DEFAULT 0.00,
  `quantity`      INT          NOT NULL DEFAULT 1,
  `line_total`    DECIMAL(10,2) NOT NULL DEFAULT 0.00,
  PRIMARY KEY (`id`),
  KEY `idx_items_order` (`order_id`),
  CONSTRAINT `fk_items_order` FOREIGN KEY (`order_id`) REFERENCES `orders`(`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ---------------------------------------------------------------------
-- Relación con clientas
-- ---------------------------------------------------------------------

DROP TABLE IF EXISTS `subscribers`;
CREATE TABLE `subscribers` (
  `id`           INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `email`        VARCHAR(190) NOT NULL,
  `name`         VARCHAR(120) NOT NULL DEFAULT '',
  `lang`         CHAR(2)      NOT NULL DEFAULT 'es',
  `status`       ENUM('pending','active','unsubscribed') NOT NULL DEFAULT 'pending',
  `token`        VARCHAR(64)  NOT NULL DEFAULT '',
  `source`       VARCHAR(40)  NOT NULL DEFAULT 'footer',
  `created_at`   DATETIME     NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `confirmed_at` DATETIME     NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `uq_subs_email` (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

DROP TABLE IF EXISTS `messages`;
CREATE TABLE `messages` (
  `id`         INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `name`       VARCHAR(160) NOT NULL,
  `email`      VARCHAR(190) NOT NULL,
  `subject`    VARCHAR(190) NOT NULL DEFAULT '',
  `body`       TEXT         NOT NULL,
  `lang`       CHAR(2)      NOT NULL DEFAULT 'es',
  `is_read`    TINYINT(1)   NOT NULL DEFAULT 0,
  `created_at` DATETIME     NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `idx_msg_read` (`is_read`,`created_at`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

SET FOREIGN_KEY_CHECKS = 1;
