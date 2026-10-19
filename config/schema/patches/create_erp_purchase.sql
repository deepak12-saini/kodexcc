-- ERP purchasing: request, quotation, purchase order, goods receipt, supplier bill. Safe to re-run.

CREATE TABLE IF NOT EXISTS `erp_purchase_requests` (
  `id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `request_no` VARCHAR(40) NOT NULL,
  `requested_on` DATE NULL,
  `requested_by` VARCHAR(120) NULL,
  `status` VARCHAR(20) NOT NULL DEFAULT 'draft',
  `notes` TEXT NULL,
  `created_by` INT UNSIGNED NULL,
  `created` DATETIME NULL,
  `modified` DATETIME NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `uk_erp_pr_no` (`request_no`),
  KEY `idx_erp_pr_status` (`status`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS `erp_purchase_request_lines` (
  `id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `request_id` INT UNSIGNED NOT NULL,
  `iso_material_id` INT UNSIGNED NULL,
  `item_name` VARCHAR(160) NOT NULL,
  `quantity` DECIMAL(14,3) NOT NULL DEFAULT 0,
  `unit_id` INT UNSIGNED NULL,
  `notes` VARCHAR(255) NULL,
  PRIMARY KEY (`id`),
  KEY `idx_erp_pr_lines_req` (`request_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS `erp_quotations` (
  `id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `quotation_no` VARCHAR(40) NOT NULL,
  `request_id` INT UNSIGNED NULL,
  `partner_id` INT UNSIGNED NOT NULL,
  `quoted_on` DATE NULL,
  `valid_until` DATE NULL,
  `status` VARCHAR(20) NOT NULL DEFAULT 'open',
  `notes` TEXT NULL,
  `created_by` INT UNSIGNED NULL,
  `created` DATETIME NULL,
  `modified` DATETIME NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `uk_erp_qt_no` (`quotation_no`),
  KEY `idx_erp_qt_partner` (`partner_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS `erp_quotation_lines` (
  `id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `quotation_id` INT UNSIGNED NOT NULL,
  `iso_material_id` INT UNSIGNED NULL,
  `item_name` VARCHAR(160) NOT NULL,
  `quantity` DECIMAL(14,3) NOT NULL DEFAULT 0,
  `unit_id` INT UNSIGNED NULL,
  `rate` DECIMAL(14,2) NOT NULL DEFAULT 0,
  `gst_percent` DECIMAL(5,2) NOT NULL DEFAULT 0,
  `amount` DECIMAL(14,2) NOT NULL DEFAULT 0,
  PRIMARY KEY (`id`),
  KEY `idx_erp_qt_lines` (`quotation_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS `erp_purchase_orders` (
  `id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `po_no` VARCHAR(40) NOT NULL,
  `quotation_id` INT UNSIGNED NULL,
  `request_id` INT UNSIGNED NULL,
  `partner_id` INT UNSIGNED NOT NULL,
  `order_date` DATE NULL,
  `expected_on` DATE NULL,
  `status` VARCHAR(20) NOT NULL DEFAULT 'draft',
  `notes` TEXT NULL,
  `created_by` INT UNSIGNED NULL,
  `created` DATETIME NULL,
  `modified` DATETIME NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `uk_erp_po_no` (`po_no`),
  KEY `idx_erp_po_partner` (`partner_id`),
  KEY `idx_erp_po_status` (`status`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS `erp_purchase_order_lines` (
  `id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `order_id` INT UNSIGNED NOT NULL,
  `iso_material_id` INT UNSIGNED NULL,
  `item_name` VARCHAR(160) NOT NULL,
  `quantity` DECIMAL(14,3) NOT NULL DEFAULT 0,
  `received_qty` DECIMAL(14,3) NOT NULL DEFAULT 0,
  `unit_id` INT UNSIGNED NULL,
  `rate` DECIMAL(14,2) NOT NULL DEFAULT 0,
  `gst_percent` DECIMAL(5,2) NOT NULL DEFAULT 0,
  `amount` DECIMAL(14,2) NOT NULL DEFAULT 0,
  PRIMARY KEY (`id`),
  KEY `idx_erp_po_lines` (`order_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS `erp_goods_receipts` (
  `id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `grn_no` VARCHAR(40) NOT NULL,
  `order_id` INT UNSIGNED NOT NULL,
  `partner_id` INT UNSIGNED NOT NULL,
  `received_on` DATE NULL,
  `received_by` VARCHAR(120) NULL,
  `notes` TEXT NULL,
  `created_by` INT UNSIGNED NULL,
  `created` DATETIME NULL,
  `modified` DATETIME NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `uk_erp_grn_no` (`grn_no`),
  KEY `idx_erp_grn_order` (`order_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS `erp_goods_receipt_lines` (
  `id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `receipt_id` INT UNSIGNED NOT NULL,
  `order_line_id` INT UNSIGNED NOT NULL,
  `iso_material_id` INT UNSIGNED NULL,
  `item_name` VARCHAR(160) NOT NULL,
  `lot_no` VARCHAR(80) NULL,
  `quantity` DECIMAL(14,3) NOT NULL DEFAULT 0,
  `unit_id` INT UNSIGNED NULL,
  `iso_receipt_id` INT UNSIGNED NULL,
  PRIMARY KEY (`id`),
  KEY `idx_erp_grn_lines` (`receipt_id`),
  KEY `idx_erp_grn_iso` (`iso_receipt_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS `erp_ap_invoices` (
  `id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `bill_no` VARCHAR(40) NOT NULL,
  `supplier_bill_no` VARCHAR(60) NULL,
  `partner_id` INT UNSIGNED NOT NULL,
  `receipt_id` INT UNSIGNED NULL,
  `order_id` INT UNSIGNED NULL,
  `bill_date` DATE NULL,
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
  UNIQUE KEY `uk_erp_bill_no` (`bill_no`),
  KEY `idx_erp_bill_partner` (`partner_id`),
  KEY `idx_erp_bill_receipt` (`receipt_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS `erp_ap_invoice_lines` (
  `id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `invoice_id` INT UNSIGNED NOT NULL,
  `item_name` VARCHAR(160) NOT NULL,
  `quantity` DECIMAL(14,3) NOT NULL DEFAULT 0,
  `rate` DECIMAL(14,2) NOT NULL DEFAULT 0,
  `gst_percent` DECIMAL(5,2) NOT NULL DEFAULT 0,
  `amount` DECIMAL(14,2) NOT NULL DEFAULT 0,
  PRIMARY KEY (`id`),
  KEY `idx_erp_bill_lines` (`invoice_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
