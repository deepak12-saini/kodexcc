-- ISO 9001 records for the Kodexcc admin panel (/admin/iso)

CREATE TABLE IF NOT EXISTS `iso_documents` (
  `id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `doc_no` VARCHAR(40) NOT NULL,
  `title` VARCHAR(200) NOT NULL,
  `doc_type` VARCHAR(40) NOT NULL DEFAULT 'sop',
  `department` VARCHAR(80) NULL,
  `revision` VARCHAR(20) NOT NULL DEFAULT '1',
  `status` VARCHAR(20) NOT NULL DEFAULT 'draft',
  `file_path` VARCHAR(255) NULL,
  `approved_by` VARCHAR(120) NULL,
  `approved_at` DATE NULL,
  `notes` TEXT NULL,
  `created_by` INT UNSIGNED NULL,
  `created` DATETIME NULL,
  `modified` DATETIME NULL,
  PRIMARY KEY (`id`),
  KEY `idx_iso_documents_status` (`status`),
  KEY `idx_iso_documents_type` (`doc_type`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS `iso_document_revisions` (
  `id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `document_id` INT UNSIGNED NOT NULL,
  `doc_no` VARCHAR(40) NULL,
  `title` VARCHAR(200) NULL,
  `revision` VARCHAR(20) NOT NULL,
  `status` VARCHAR(20) NULL,
  `file_path` VARCHAR(255) NULL,
  `notes` TEXT NULL,
  `created_by` INT UNSIGNED NULL,
  `created` DATETIME NULL,
  PRIMARY KEY (`id`),
  KEY `idx_iso_docrev_doc` (`document_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS `iso_training` (
  `id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `document_id` INT UNSIGNED NULL,
  `person_name` VARCHAR(120) NOT NULL,
  `trainer_name` VARCHAR(120) NULL,
  `trained_on` DATE NULL,
  `department` VARCHAR(80) NULL,
  `notes` TEXT NULL,
  `created_by` INT UNSIGNED NULL,
  `created` DATETIME NULL,
  `modified` DATETIME NULL,
  PRIMARY KEY (`id`),
  KEY `idx_iso_training_doc` (`document_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS `iso_suppliers` (
  `id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `name` VARCHAR(160) NOT NULL,
  `material` VARCHAR(160) NULL,
  `contact` VARCHAR(120) NULL,
  `phone` VARCHAR(40) NULL,
  `status` VARCHAR(20) NOT NULL DEFAULT 'approved',
  `notes` TEXT NULL,
  `created_by` INT UNSIGNED NULL,
  `created` DATETIME NULL,
  `modified` DATETIME NULL,
  PRIMARY KEY (`id`),
  KEY `idx_iso_suppliers_status` (`status`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS `iso_materials` (
  `id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `name` VARCHAR(160) NOT NULL,
  `supplier_id` INT UNSIGNED NULL,
  `spec` VARCHAR(200) NULL,
  `status` VARCHAR(20) NOT NULL DEFAULT 'active',
  `notes` TEXT NULL,
  `created_by` INT UNSIGNED NULL,
  `created` DATETIME NULL,
  `modified` DATETIME NULL,
  PRIMARY KEY (`id`),
  KEY `idx_iso_materials_supplier` (`supplier_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS `iso_receipts` (
  `id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `receipt_no` VARCHAR(40) NOT NULL,
  `supplier_id` INT UNSIGNED NULL,
  `material` VARCHAR(160) NOT NULL,
  `lot_no` VARCHAR(80) NULL,
  `quantity` VARCHAR(40) NULL,
  `received_on` DATE NULL,
  `received_by` VARCHAR(120) NULL,
  `inspection_result` VARCHAR(20) NOT NULL DEFAULT 'pending',
  `inspected_by` VARCHAR(120) NULL,
  `inspected_on` DATE NULL,
  `remarks` TEXT NULL,
  `nonconformance_id` INT UNSIGNED NULL,
  `created_by` INT UNSIGNED NULL,
  `created` DATETIME NULL,
  `modified` DATETIME NULL,
  PRIMARY KEY (`id`),
  KEY `idx_iso_receipts_result` (`inspection_result`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS `iso_batches` (
  `id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `batch_no` VARCHAR(40) NOT NULL,
  `product` VARCHAR(160) NOT NULL,
  `machine` VARCHAR(80) NULL,
  `operator_names` VARCHAR(255) NULL,
  `supervisor_name` VARCHAR(120) NULL,
  `started_on` DATE NULL,
  `ended_on` DATE NULL,
  `notes` TEXT NULL,
  `created_by` INT UNSIGNED NULL,
  `created` DATETIME NULL,
  `modified` DATETIME NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS `iso_inspections` (
  `id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `inspection_no` VARCHAR(40) NOT NULL,
  `stage` VARCHAR(20) NOT NULL DEFAULT 'incoming',
  `receipt_id` INT UNSIGNED NULL,
  `batch_id` INT UNSIGNED NULL,
  `result` VARCHAR(20) NOT NULL DEFAULT 'pass',
  `inspected_by` VARCHAR(120) NULL,
  `inspected_on` DATE NULL,
  `remarks` TEXT NULL,
  `nonconformance_id` INT UNSIGNED NULL,
  `created_by` INT UNSIGNED NULL,
  `created` DATETIME NULL,
  `modified` DATETIME NULL,
  PRIMARY KEY (`id`),
  KEY `idx_iso_inspections_result` (`result`),
  KEY `idx_iso_inspections_stage` (`stage`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS `iso_nonconformances` (
  `id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `ncr_no` VARCHAR(40) NOT NULL,
  `source` VARCHAR(20) NOT NULL DEFAULT 'other',
  `department` VARCHAR(80) NULL,
  `receipt_id` INT UNSIGNED NULL,
  `batch_id` INT UNSIGNED NULL,
  `description` TEXT NULL,
  `status` VARCHAR(20) NOT NULL DEFAULT 'open',
  `created_by` INT UNSIGNED NULL,
  `created` DATETIME NULL,
  `modified` DATETIME NULL,
  PRIMARY KEY (`id`),
  KEY `idx_iso_ncr_status` (`status`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS `iso_actions` (
  `id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `action_no` VARCHAR(40) NOT NULL,
  `nonconformance_id` INT UNSIGNED NULL,
  `cause` TEXT NULL,
  `action_text` TEXT NULL,
  `owner_name` VARCHAR(120) NULL,
  `due_date` DATE NULL,
  `evidence` TEXT NULL,
  `status` VARCHAR(20) NOT NULL DEFAULT 'open',
  `closed_by` VARCHAR(120) NULL,
  `closed_on` DATE NULL,
  `created_by` INT UNSIGNED NULL,
  `created` DATETIME NULL,
  `modified` DATETIME NULL,
  PRIMARY KEY (`id`),
  KEY `idx_iso_actions_status` (`status`),
  KEY `idx_iso_actions_ncr` (`nonconformance_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS `iso_maintenance` (
  `id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `machine` VARCHAR(80) NOT NULL,
  `work_done` TEXT NULL,
  `done_on` DATE NULL,
  `next_due` DATE NULL,
  `done_by` VARCHAR(120) NULL,
  `notes` TEXT NULL,
  `created_by` INT UNSIGNED NULL,
  `created` DATETIME NULL,
  `modified` DATETIME NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS `iso_audits` (
  `id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `audit_no` VARCHAR(40) NOT NULL,
  `title` VARCHAR(200) NOT NULL,
  `planned_on` DATE NULL,
  `auditor_name` VARCHAR(120) NULL,
  `findings` TEXT NULL,
  `status` VARCHAR(20) NOT NULL DEFAULT 'planned',
  `nonconformance_id` INT UNSIGNED NULL,
  `created_by` INT UNSIGNED NULL,
  `created` DATETIME NULL,
  `modified` DATETIME NULL,
  PRIMARY KEY (`id`),
  KEY `idx_iso_audits_status` (`status`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS `iso_complaints` (
  `id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `complaint_no` VARCHAR(40) NOT NULL,
  `customer_name` VARCHAR(160) NOT NULL,
  `product` VARCHAR(160) NULL,
  `batch_id` INT UNSIGNED NULL,
  `description` TEXT NULL,
  `status` VARCHAR(20) NOT NULL DEFAULT 'open',
  `nonconformance_id` INT UNSIGNED NULL,
  `created_by` INT UNSIGNED NULL,
  `created` DATETIME NULL,
  `modified` DATETIME NULL,
  PRIMARY KEY (`id`),
  KEY `idx_iso_complaints_status` (`status`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS `iso_reviews` (
  `id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `meeting_on` DATE NULL,
  `attendees` VARCHAR(255) NULL,
  `decisions` TEXT NULL,
  `created_by` INT UNSIGNED NULL,
  `created` DATETIME NULL,
  `modified` DATETIME NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
