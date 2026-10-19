-- ERP foundation: administration and business partners. Safe to re-run.

CREATE TABLE IF NOT EXISTS `erp_settings` (
  `id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `setting_key` VARCHAR(80) NOT NULL,
  `setting_value` TEXT NULL,
  `modified` DATETIME NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `uk_erp_settings_key` (`setting_key`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT IGNORE INTO `erp_settings` (`setting_key`, `setting_value`, `modified`) VALUES
('company_name', 'Kodex', NOW()),
('gstin', '', NOW()),
('state', 'Punjab', NOW()),
('address', '', NOW()),
('financial_year_start', '04-01', NOW()),
('currency', 'INR', NOW());

CREATE TABLE IF NOT EXISTS `erp_units` (
  `id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `code` VARCHAR(20) NOT NULL,
  `name` VARCHAR(80) NOT NULL,
  `status` VARCHAR(20) NOT NULL DEFAULT 'active',
  `created_by` INT UNSIGNED NULL,
  `created` DATETIME NULL,
  `modified` DATETIME NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `uk_erp_units_code` (`code`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT IGNORE INTO `erp_units` (`code`, `name`, `status`, `created`, `modified`) VALUES
('KG', 'Kilogram', 'active', NOW(), NOW()),
('LTR', 'Litre', 'active', NOW(), NOW()),
('NOS', 'Numbers', 'active', NOW(), NOW()),
('PCS', 'Pieces', 'active', NOW(), NOW()),
('BOX', 'Box', 'active', NOW(), NOW()),
('DRUM', 'Drum', 'active', NOW(), NOW());

CREATE TABLE IF NOT EXISTS `erp_godowns` (
  `id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `code` VARCHAR(30) NOT NULL,
  `name` VARCHAR(120) NOT NULL,
  `location` VARCHAR(160) NULL,
  `status` VARCHAR(20) NOT NULL DEFAULT 'active',
  `created_by` INT UNSIGNED NULL,
  `created` DATETIME NULL,
  `modified` DATETIME NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `uk_erp_godowns_code` (`code`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT IGNORE INTO `erp_godowns` (`code`, `name`, `location`, `status`, `created`, `modified`) VALUES
('RM', 'Raw material store', 'Factory', 'active', NOW(), NOW()),
('FG', 'Finished goods store', 'Factory', 'active', NOW(), NOW()),
('QC', 'QC hold', 'Factory', 'active', NOW(), NOW());

CREATE TABLE IF NOT EXISTS `erp_partners` (
  `id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `code` VARCHAR(30) NOT NULL,
  `partner_type` VARCHAR(20) NOT NULL DEFAULT 'customer',
  `name` VARCHAR(160) NOT NULL,
  `gstin` VARCHAR(20) NULL,
  `pan` VARCHAR(20) NULL,
  `state` VARCHAR(60) NULL,
  `city` VARCHAR(80) NULL,
  `address` TEXT NULL,
  `contact_person` VARCHAR(120) NULL,
  `phone` VARCHAR(40) NULL,
  `email` VARCHAR(160) NULL,
  `credit_days` INT NULL,
  `credit_limit` DECIMAL(14,2) NULL,
  `iso_supplier_id` INT UNSIGNED NULL,
  `status` VARCHAR(20) NOT NULL DEFAULT 'active',
  `notes` TEXT NULL,
  `created_by` INT UNSIGNED NULL,
  `created` DATETIME NULL,
  `modified` DATETIME NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `uk_erp_partners_code` (`code`),
  KEY `idx_erp_partners_type` (`partner_type`),
  KEY `idx_erp_partners_iso` (`iso_supplier_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS `erp_audit_logs` (
  `id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `actor` VARCHAR(120) NULL,
  `action` VARCHAR(40) NOT NULL,
  `entity` VARCHAR(60) NOT NULL,
  `entity_id` INT UNSIGNED NULL,
  `summary` VARCHAR(255) NULL,
  `created` DATETIME NULL,
  PRIMARY KEY (`id`),
  KEY `idx_erp_audit_entity` (`entity`, `entity_id`),
  KEY `idx_erp_audit_created` (`created`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
