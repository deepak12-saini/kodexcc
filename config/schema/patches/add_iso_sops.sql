CREATE TABLE IF NOT EXISTS `iso_sops` (
  `id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `sop_no` VARCHAR(40) NOT NULL,
  `title` VARCHAR(200) NOT NULL,
  `department` VARCHAR(80) NULL,
  `revision` VARCHAR(20) NOT NULL DEFAULT '1',
  `status` VARCHAR(20) NOT NULL DEFAULT 'draft',
  `file_path` VARCHAR(255) NULL,
  `next_review_on` DATE NULL,
  `prepared_by` VARCHAR(120) NULL,
  `prepared_on` DATE NULL,
  `reviewed_by` VARCHAR(120) NULL,
  `reviewed_on` DATE NULL,
  `approved_by` VARCHAR(120) NULL,
  `approved_on` DATE NULL,
  `notes` TEXT NULL,
  `created_by` INT UNSIGNED NULL,
  `created` DATETIME NULL,
  `modified` DATETIME NULL,
  PRIMARY KEY (`id`),
  KEY `idx_iso_sops_department` (`department`),
  KEY `idx_iso_sops_status` (`status`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS `iso_sop_revisions` (
  `id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `sop_id` INT UNSIGNED NOT NULL,
  `revision` VARCHAR(20) NOT NULL,
  `title` VARCHAR(200) NULL,
  `status` VARCHAR(20) NULL,
  `file_path` VARCHAR(255) NULL,
  `obsolete` TINYINT(1) NOT NULL DEFAULT 1,
  `created` DATETIME NULL,
  PRIMARY KEY (`id`),
  KEY `idx_iso_soprev_sop` (`sop_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS `iso_sop_reviews` (
  `id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `sop_id` INT UNSIGNED NOT NULL,
  `employee_id` INT UNSIGNED NULL,
  `employee_name` VARCHAR(120) NOT NULL,
  `result` VARCHAR(20) NOT NULL,
  `reason` TEXT NOT NULL,
  `created` DATETIME NULL,
  PRIMARY KEY (`id`),
  KEY `idx_iso_sopreview_sop` (`sop_id`),
  KEY `idx_iso_sopreview_employee` (`employee_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
