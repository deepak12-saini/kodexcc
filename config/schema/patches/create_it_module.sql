-- Admin & IT module. Separate from hr_assets. Safe to re-run.

CREATE TABLE IF NOT EXISTS `it_vendors` (
  `id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `name` VARCHAR(160) NOT NULL,
  `contact_person` VARCHAR(120) NULL,
  `phone` VARCHAR(40) NULL,
  `email` VARCHAR(160) NULL,
  `address` TEXT NULL,
  `gst_number` VARCHAR(40) NULL,
  `warranty_notes` TEXT NULL,
  `notes` TEXT NULL,
  `status` TINYINT(1) NOT NULL DEFAULT 1,
  `created` DATETIME NULL,
  `modified` DATETIME NULL,
  PRIMARY KEY (`id`),
  KEY `idx_it_vendors_name` (`name`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS `it_assets` (
  `id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `asset_code` VARCHAR(60) NOT NULL,
  `asset_type` VARCHAR(30) NOT NULL DEFAULT 'other',
  `brand` VARCHAR(80) NULL,
  `model` VARCHAR(120) NULL,
  `serial_number` VARCHAR(120) NULL,
  `processor` VARCHAR(120) NULL,
  `ram` VARCHAR(60) NULL,
  `storage` VARCHAR(80) NULL,
  `purchase_date` DATE NULL,
  `purchase_cost` DECIMAL(12,2) NULL,
  `vendor_id` INT UNSIGNED NULL,
  `warranty_until` DATE NULL,
  `warranty_notes` VARCHAR(255) NULL,
  `location` VARCHAR(160) NULL,
  `status` VARCHAR(30) NOT NULL DEFAULT 'available',
  `notes` TEXT NULL,
  `purchase_request_id` INT UNSIGNED NULL,
  `created` DATETIME NULL,
  `modified` DATETIME NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `uk_it_assets_code` (`asset_code`),
  KEY `idx_it_assets_type` (`asset_type`),
  KEY `idx_it_assets_status` (`status`),
  KEY `idx_it_assets_vendor` (`vendor_id`),
  KEY `idx_it_assets_serial` (`serial_number`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS `it_asset_events` (
  `id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `asset_id` INT UNSIGNED NOT NULL,
  `event_type` VARCHAR(40) NOT NULL,
  `from_status` VARCHAR(30) NULL,
  `to_status` VARCHAR(30) NULL,
  `summary` VARCHAR(255) NOT NULL,
  `notes` TEXT NULL,
  `actor_user_id` INT UNSIGNED NULL,
  `created` DATETIME NULL,
  PRIMARY KEY (`id`),
  KEY `idx_it_asset_events_asset` (`asset_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS `it_asset_assignments` (
  `id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `asset_id` INT UNSIGNED NOT NULL,
  `employee_id` INT UNSIGNED NOT NULL,
  `assigned_date` DATE NOT NULL,
  `return_date` DATE NULL,
  `condition_on_assign` VARCHAR(80) NULL,
  `condition_on_return` VARCHAR(80) NULL,
  `notes` TEXT NULL,
  `status` VARCHAR(20) NOT NULL DEFAULT 'assigned',
  `created` DATETIME NULL,
  `modified` DATETIME NULL,
  PRIMARY KEY (`id`),
  KEY `idx_it_assign_asset` (`asset_id`),
  KEY `idx_it_assign_emp` (`employee_id`),
  KEY `idx_it_assign_status` (`status`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS `it_tickets` (
  `id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `ticket_no` VARCHAR(40) NOT NULL,
  `employee_id` INT UNSIGNED NULL,
  `asset_id` INT UNSIGNED NULL,
  `category` VARCHAR(40) NOT NULL DEFAULT 'other',
  `priority` VARCHAR(20) NOT NULL DEFAULT 'medium',
  `problem` TEXT NOT NULL,
  `assigned_user_id` INT UNSIGNED NULL,
  `status` VARCHAR(20) NOT NULL DEFAULT 'open',
  `resolution` TEXT NULL,
  `resolved_at` DATETIME NULL,
  `closed_at` DATETIME NULL,
  `created` DATETIME NULL,
  `modified` DATETIME NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `uk_it_tickets_no` (`ticket_no`),
  KEY `idx_it_tickets_status` (`status`),
  KEY `idx_it_tickets_emp` (`employee_id`),
  KEY `idx_it_tickets_asset` (`asset_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS `it_ticket_updates` (
  `id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `ticket_id` INT UNSIGNED NOT NULL,
  `status` VARCHAR(20) NULL,
  `note` TEXT NULL,
  `actor_user_id` INT UNSIGNED NULL,
  `created` DATETIME NULL,
  PRIMARY KEY (`id`),
  KEY `idx_it_ticket_updates` (`ticket_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS `it_repairs` (
  `id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `repair_no` VARCHAR(40) NOT NULL,
  `asset_id` INT UNSIGNED NOT NULL,
  `ticket_id` INT UNSIGNED NULL,
  `vendor_id` INT UNSIGNED NULL,
  `problem` TEXT NULL,
  `diagnosis` TEXT NULL,
  `required_parts` TEXT NULL,
  `estimated_cost` DECIMAL(12,2) NULL,
  `actual_cost` DECIMAL(12,2) NULL,
  `sent_date` DATE NULL,
  `expected_return_date` DATE NULL,
  `returned_date` DATE NULL,
  `under_warranty` TINYINT(1) NOT NULL DEFAULT 0,
  `status` VARCHAR(30) NOT NULL DEFAULT 'pending',
  `notes` TEXT NULL,
  `created` DATETIME NULL,
  `modified` DATETIME NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `uk_it_repairs_no` (`repair_no`),
  KEY `idx_it_repairs_asset` (`asset_id`),
  KEY `idx_it_repairs_ticket` (`ticket_id`),
  KEY `idx_it_repairs_status` (`status`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS `it_repair_files` (
  `id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `repair_id` INT UNSIGNED NOT NULL,
  `title` VARCHAR(160) NULL,
  `file_path` VARCHAR(255) NOT NULL,
  `uploaded_by` INT UNSIGNED NULL,
  `created` DATETIME NULL,
  PRIMARY KEY (`id`),
  KEY `idx_it_repair_files` (`repair_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS `it_purchase_requests` (
  `id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `request_no` VARCHAR(40) NOT NULL,
  `requested_by` INT UNSIGNED NULL,
  `department_id` INT UNSIGNED NULL,
  `purpose` TEXT NULL,
  `item_name` VARCHAR(200) NOT NULL,
  `quantity` INT NOT NULL DEFAULT 1,
  `specifications` TEXT NULL,
  `estimated_cost` DECIMAL(12,2) NULL,
  `vendor_id` INT UNSIGNED NULL,
  `quotation_notes` TEXT NULL,
  `quotation_file` VARCHAR(255) NULL,
  `approval_status` VARCHAR(30) NOT NULL DEFAULT 'requested',
  `approved_by` INT UNSIGNED NULL,
  `approved_at` DATETIME NULL,
  `purchase_date` DATE NULL,
  `actual_cost` DECIMAL(12,2) NULL,
  `invoice_no` VARCHAR(80) NULL,
  `invoice_file` VARCHAR(255) NULL,
  `asset_id` INT UNSIGNED NULL,
  `notes` TEXT NULL,
  `created` DATETIME NULL,
  `modified` DATETIME NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `uk_it_pr_no` (`request_no`),
  KEY `idx_it_pr_status` (`approval_status`),
  KEY `idx_it_pr_vendor` (`vendor_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS `it_maintenance` (
  `id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `asset_id` INT UNSIGNED NOT NULL,
  `maintenance_type` VARCHAR(80) NOT NULL,
  `description` TEXT NULL,
  `scheduled_date` DATE NULL,
  `completed_date` DATE NULL,
  `cost` DECIMAL(12,2) NULL,
  `vendor_id` INT UNSIGNED NULL,
  `performed_by` VARCHAR(120) NULL,
  `status` VARCHAR(20) NOT NULL DEFAULT 'scheduled',
  `notes` TEXT NULL,
  `created` DATETIME NULL,
  `modified` DATETIME NULL,
  PRIMARY KEY (`id`),
  KEY `idx_it_maint_asset` (`asset_id`),
  KEY `idx_it_maint_status` (`status`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
