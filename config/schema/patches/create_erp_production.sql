-- ERP production: BOM, production order, material issue, finished output. Safe to re-run.

CREATE TABLE IF NOT EXISTS `erp_boms` (
  `id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `bom_no` VARCHAR(40) NOT NULL,
  `item_id` INT UNSIGNED NOT NULL,
  `status` VARCHAR(20) NOT NULL DEFAULT 'active',
  `notes` TEXT NULL,
  `created_by` INT UNSIGNED NULL,
  `created` DATETIME NULL,
  `modified` DATETIME NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `uk_erp_bom_no` (`bom_no`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS `erp_bom_lines` (
  `id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `bom_id` INT UNSIGNED NOT NULL,
  `item_id` INT UNSIGNED NOT NULL,
  `quantity` DECIMAL(14,3) NOT NULL DEFAULT 0,
  PRIMARY KEY (`id`),
  KEY `idx_erp_bom_lines` (`bom_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS `erp_production_orders` (
  `id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `prod_no` VARCHAR(40) NOT NULL,
  `item_id` INT UNSIGNED NOT NULL,
  `bom_id` INT UNSIGNED NOT NULL,
  `planned_qty` DECIMAL(14,3) NOT NULL DEFAULT 0,
  `status` VARCHAR(20) NOT NULL DEFAULT 'draft',
  `output_qty` DECIMAL(14,3) NOT NULL DEFAULT 0,
  `output_lot` VARCHAR(80) NULL,
  `output_godown_id` INT UNSIGNED NULL,
  `iso_batch_id` INT UNSIGNED NULL,
  `started_on` DATE NULL,
  `notes` TEXT NULL,
  `created_by` INT UNSIGNED NULL,
  `created` DATETIME NULL,
  `modified` DATETIME NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `uk_erp_prod_no` (`prod_no`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS `erp_production_issues` (
  `id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `order_id` INT UNSIGNED NOT NULL,
  `item_id` INT UNSIGNED NOT NULL,
  `godown_id` INT UNSIGNED NOT NULL,
  `lot_no` VARCHAR(80) NOT NULL DEFAULT '',
  `quantity` DECIMAL(14,3) NOT NULL DEFAULT 0,
  PRIMARY KEY (`id`),
  KEY `idx_erp_prod_issue` (`order_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
