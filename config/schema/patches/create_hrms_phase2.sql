-- HRMS Phase 2: requests, holidays, audit (safe to re-run via apply script)

CREATE TABLE IF NOT EXISTS `hr_request_types` (
  `id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `code` VARCHAR(40) NOT NULL,
  `name` VARCHAR(120) NOT NULL,
  `needs_asset` TINYINT(1) NOT NULL DEFAULT 0,
  `status` TINYINT(1) NOT NULL DEFAULT 1,
  `created` DATETIME NULL,
  `modified` DATETIME NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `uk_hr_request_types_code` (`code`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS `hr_requests` (
  `id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `request_no` VARCHAR(40) NOT NULL,
  `employee_id` INT UNSIGNED NOT NULL,
  `request_type_id` INT UNSIGNED NOT NULL,
  `title` VARCHAR(200) NOT NULL,
  `description` TEXT NULL,
  `priority` VARCHAR(20) NOT NULL DEFAULT 'normal',
  `status` VARCHAR(20) NOT NULL DEFAULT 'pending',
  `asset_category` VARCHAR(80) NULL,
  `linked_asset_id` INT UNSIGNED NULL,
  `hr_remark` TEXT NULL,
  `reviewed_by` INT UNSIGNED NULL,
  `reviewed_at` DATETIME NULL,
  `created` DATETIME NULL,
  `modified` DATETIME NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `uk_hr_requests_no` (`request_no`),
  KEY `idx_hr_requests_emp` (`employee_id`),
  KEY `idx_hr_requests_status` (`status`),
  KEY `idx_hr_requests_type` (`request_type_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS `hr_holidays` (
  `id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `name` VARCHAR(160) NOT NULL,
  `holiday_date` DATE NOT NULL,
  `type` VARCHAR(20) NOT NULL DEFAULT 'public',
  `is_optional` TINYINT(1) NOT NULL DEFAULT 0,
  `status` TINYINT(1) NOT NULL DEFAULT 1,
  `created` DATETIME NULL,
  `modified` DATETIME NULL,
  PRIMARY KEY (`id`),
  KEY `idx_hr_holidays_date` (`holiday_date`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS `hr_audit_logs` (
  `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
  `actor_user_id` INT UNSIGNED NULL,
  `actor_role` VARCHAR(20) NULL,
  `action` VARCHAR(60) NOT NULL,
  `entity_type` VARCHAR(60) NOT NULL,
  `entity_id` INT UNSIGNED NULL,
  `employee_id` INT UNSIGNED NULL,
  `summary` VARCHAR(255) NOT NULL,
  `before_json` MEDIUMTEXT NULL,
  `after_json` MEDIUMTEXT NULL,
  `ip` VARCHAR(45) NULL,
  `created` DATETIME NULL,
  PRIMARY KEY (`id`),
  KEY `idx_hr_audit_created` (`created`),
  KEY `idx_hr_audit_entity` (`entity_type`, `entity_id`),
  KEY `idx_hr_audit_actor` (`actor_user_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Proposed times for attendance corrections (Phase 2)
ALTER TABLE `hr_attendances`
  ADD COLUMN IF NOT EXISTS `proposed_clock_in` DATETIME NULL AFTER `correction_status`,
  ADD COLUMN IF NOT EXISTS `proposed_clock_out` DATETIME NULL AFTER `proposed_clock_in`;

INSERT IGNORE INTO `hr_request_types` (`id`, `code`, `name`, `needs_asset`, `status`, `created`, `modified`) VALUES
(1, 'asset', 'Asset Request', 1, 1, NOW(), NOW()),
(2, 'asset_repair', 'Asset Repair / Replacement', 1, 1, NOW(), NOW()),
(3, 'id_card', 'ID Card Request', 0, 1, NOW(), NOW()),
(4, 'document', 'Document Request', 0, 1, NOW(), NOW()),
(5, 'general', 'General HR Request', 0, 1, NOW(), NOW());

INSERT IGNORE INTO `hr_holidays` (`id`, `name`, `holiday_date`, `type`, `is_optional`, `status`, `created`, `modified`) VALUES
(1, 'Republic Day', CONCAT(YEAR(CURDATE()), '-01-26'), 'public', 0, 1, NOW(), NOW()),
(2, 'Independence Day', CONCAT(YEAR(CURDATE()), '-08-15'), 'public', 0, 1, NOW(), NOW()),
(3, 'Gandhi Jayanti', CONCAT(YEAR(CURDATE()), '-10-02'), 'public', 0, 1, NOW(), NOW()),
(4, 'Christmas', CONCAT(YEAR(CURDATE()), '-12-25'), 'public', 0, 1, NOW(), NOW()),
(5, 'Company Foundation Day', CONCAT(YEAR(CURDATE()), '-03-15'), 'company', 0, 1, NOW(), NOW()),
(6, 'Year End Closure', CONCAT(YEAR(CURDATE()), '-12-31'), 'company', 1, 1, NOW(), NOW());
