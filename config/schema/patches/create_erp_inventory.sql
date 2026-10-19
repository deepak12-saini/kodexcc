-- ERP inventory: items, quantity ledger, transfers, adjustments. Safe to re-run.

CREATE TABLE IF NOT EXISTS `erp_items` (
  `id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `code` VARCHAR(30) NOT NULL,
  `name` VARCHAR(160) NOT NULL,
  `item_type` VARCHAR(20) NOT NULL DEFAULT 'raw',
  `iso_material_id` INT UNSIGNED NULL,
  `unit_id` INT UNSIGNED NULL,
  `reorder_level` DECIMAL(14,3) NOT NULL DEFAULT 0,
  `gst_percent` DECIMAL(5,2) NOT NULL DEFAULT 18,
  `status` VARCHAR(20) NOT NULL DEFAULT 'active',
  `created_by` INT UNSIGNED NULL,
  `created` DATETIME NULL,
  `modified` DATETIME NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `uk_erp_items_code` (`code`),
  KEY `idx_erp_items_material` (`iso_material_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS `erp_stock_ledger` (
  `id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `item_id` INT UNSIGNED NOT NULL,
  `godown_id` INT UNSIGNED NOT NULL,
  `lot_no` VARCHAR(80) NOT NULL DEFAULT '',
  `qty_in` DECIMAL(14,3) NOT NULL DEFAULT 0,
  `qty_out` DECIMAL(14,3) NOT NULL DEFAULT 0,
  `ref_type` VARCHAR(40) NOT NULL,
  `ref_id` INT UNSIGNED NULL,
  `moved_on` DATE NULL,
  `notes` VARCHAR(255) NULL,
  `created` DATETIME NULL,
  PRIMARY KEY (`id`),
  KEY `idx_erp_stock_item` (`item_id`, `godown_id`, `lot_no`),
  KEY `idx_erp_stock_ref` (`ref_type`, `ref_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS `erp_transfers` (
  `id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `transfer_no` VARCHAR(40) NOT NULL,
  `item_id` INT UNSIGNED NOT NULL,
  `from_godown_id` INT UNSIGNED NOT NULL,
  `to_godown_id` INT UNSIGNED NOT NULL,
  `lot_no` VARCHAR(80) NOT NULL DEFAULT '',
  `quantity` DECIMAL(14,3) NOT NULL DEFAULT 0,
  `moved_on` DATE NULL,
  `notes` VARCHAR(255) NULL,
  `created_by` INT UNSIGNED NULL,
  `created` DATETIME NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `uk_erp_transfer_no` (`transfer_no`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS `erp_adjustments` (
  `id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `adjust_no` VARCHAR(40) NOT NULL,
  `item_id` INT UNSIGNED NOT NULL,
  `godown_id` INT UNSIGNED NOT NULL,
  `lot_no` VARCHAR(80) NOT NULL DEFAULT '',
  `direction` VARCHAR(10) NOT NULL DEFAULT 'in',
  `quantity` DECIMAL(14,3) NOT NULL DEFAULT 0,
  `reason` VARCHAR(255) NULL,
  `moved_on` DATE NULL,
  `created_by` INT UNSIGNED NULL,
  `created` DATETIME NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `uk_erp_adjust_no` (`adjust_no`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
