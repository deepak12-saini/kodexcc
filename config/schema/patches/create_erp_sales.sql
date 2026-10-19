-- ERP sales: quotation, order, delivery, A/R invoice. Safe to re-run.

CREATE TABLE IF NOT EXISTS `erp_sales_quotations` (
  `id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `quotation_no` VARCHAR(40) NOT NULL,
  `partner_id` INT UNSIGNED NOT NULL,
  `quoted_on` DATE NULL,
  `valid_until` DATE NULL,
  `status` VARCHAR(20) NOT NULL DEFAULT 'open',
  `notes` TEXT NULL,
  `created_by` INT UNSIGNED NULL,
  `created` DATETIME NULL,
  `modified` DATETIME NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `uk_erp_sq_no` (`quotation_no`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS `erp_sales_quotation_lines` (
  `id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `quotation_id` INT UNSIGNED NOT NULL,
  `item_id` INT UNSIGNED NOT NULL,
  `quantity` DECIMAL(14,3) NOT NULL DEFAULT 0,
  `rate` DECIMAL(14,2) NOT NULL DEFAULT 0,
  `gst_percent` DECIMAL(5,2) NOT NULL DEFAULT 0,
  `amount` DECIMAL(14,2) NOT NULL DEFAULT 0,
  PRIMARY KEY (`id`),
  KEY `idx_erp_sq_lines` (`quotation_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS `erp_sales_orders` (
  `id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `so_no` VARCHAR(40) NOT NULL,
  `quotation_id` INT UNSIGNED NULL,
  `partner_id` INT UNSIGNED NOT NULL,
  `order_date` DATE NULL,
  `status` VARCHAR(20) NOT NULL DEFAULT 'draft',
  `notes` TEXT NULL,
  `created_by` INT UNSIGNED NULL,
  `created` DATETIME NULL,
  `modified` DATETIME NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `uk_erp_so_no` (`so_no`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS `erp_sales_order_lines` (
  `id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `order_id` INT UNSIGNED NOT NULL,
  `item_id` INT UNSIGNED NOT NULL,
  `quantity` DECIMAL(14,3) NOT NULL DEFAULT 0,
  `delivered_qty` DECIMAL(14,3) NOT NULL DEFAULT 0,
  `rate` DECIMAL(14,2) NOT NULL DEFAULT 0,
  `gst_percent` DECIMAL(5,2) NOT NULL DEFAULT 0,
  `amount` DECIMAL(14,2) NOT NULL DEFAULT 0,
  PRIMARY KEY (`id`),
  KEY `idx_erp_so_lines` (`order_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS `erp_deliveries` (
  `id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `delivery_no` VARCHAR(40) NOT NULL,
  `order_id` INT UNSIGNED NOT NULL,
  `partner_id` INT UNSIGNED NOT NULL,
  `delivered_on` DATE NULL,
  `notes` TEXT NULL,
  `created_by` INT UNSIGNED NULL,
  `created` DATETIME NULL,
  `modified` DATETIME NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `uk_erp_dn_no` (`delivery_no`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS `erp_delivery_lines` (
  `id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `delivery_id` INT UNSIGNED NOT NULL,
  `order_line_id` INT UNSIGNED NOT NULL,
  `item_id` INT UNSIGNED NOT NULL,
  `godown_id` INT UNSIGNED NOT NULL,
  `lot_no` VARCHAR(80) NOT NULL DEFAULT '',
  `quantity` DECIMAL(14,3) NOT NULL DEFAULT 0,
  PRIMARY KEY (`id`),
  KEY `idx_erp_dn_lines` (`delivery_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS `erp_ar_invoices` (
  `id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `invoice_no` VARCHAR(40) NOT NULL,
  `partner_id` INT UNSIGNED NOT NULL,
  `delivery_id` INT UNSIGNED NULL,
  `order_id` INT UNSIGNED NULL,
  `invoice_date` DATE NULL,
  `due_date` DATE NULL,
  `status` VARCHAR(20) NOT NULL DEFAULT 'open',
  `taxable` DECIMAL(14,2) NOT NULL DEFAULT 0,
  `gst` DECIMAL(14,2) NOT NULL DEFAULT 0,
  `total` DECIMAL(14,2) NOT NULL DEFAULT 0,
  `paid_amount` DECIMAL(14,2) NOT NULL DEFAULT 0,
  `notes` TEXT NULL,
  `created_by` INT UNSIGNED NULL,
  `created` DATETIME NULL,
  `modified` DATETIME NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `uk_erp_ar_no` (`invoice_no`),
  KEY `idx_erp_ar_delivery` (`delivery_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS `erp_ar_invoice_lines` (
  `id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `invoice_id` INT UNSIGNED NOT NULL,
  `item_name` VARCHAR(160) NOT NULL,
  `quantity` DECIMAL(14,3) NOT NULL DEFAULT 0,
  `rate` DECIMAL(14,2) NOT NULL DEFAULT 0,
  `gst_percent` DECIMAL(5,2) NOT NULL DEFAULT 0,
  `amount` DECIMAL(14,2) NOT NULL DEFAULT 0,
  PRIMARY KEY (`id`),
  KEY `idx_erp_ar_lines` (`invoice_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
