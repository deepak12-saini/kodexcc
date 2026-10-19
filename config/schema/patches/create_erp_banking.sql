-- ERP banking: bank accounts and payments against purchase and sales invoices. Safe to re-run.

CREATE TABLE IF NOT EXISTS `erp_bank_accounts` (
  `id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `name` VARCHAR(120) NOT NULL,
  `account_no` VARCHAR(40) NULL,
  `account_id` INT UNSIGNED NULL,
  `opening_balance` DECIMAL(14,2) NOT NULL DEFAULT 0,
  `status` VARCHAR(20) NOT NULL DEFAULT 'active',
  `created_by` INT UNSIGNED NULL,
  `created` DATETIME NULL,
  `modified` DATETIME NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO `erp_bank_accounts` (`name`, `account_no`, `opening_balance`, `status`, `created`, `modified`)
SELECT 'Main bank', '', 0, 'active', NOW(), NOW()
WHERE NOT EXISTS (SELECT 1 FROM `erp_bank_accounts` WHERE `name` = 'Main bank');

CREATE TABLE IF NOT EXISTS `erp_payments` (
  `id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `payment_no` VARCHAR(40) NOT NULL,
  `direction` VARCHAR(10) NOT NULL,
  `bank_account_id` INT UNSIGNED NOT NULL,
  `partner_id` INT UNSIGNED NOT NULL,
  `invoice_kind` VARCHAR(10) NOT NULL,
  `invoice_id` INT UNSIGNED NOT NULL,
  `amount` DECIMAL(14,2) NOT NULL DEFAULT 0,
  `paid_on` DATE NULL,
  `reference` VARCHAR(80) NULL,
  `reconciled` TINYINT(1) NOT NULL DEFAULT 0,
  `created_by` INT UNSIGNED NULL,
  `created` DATETIME NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `uk_erp_pay_no` (`payment_no`),
  KEY `idx_erp_pay_invoice` (`invoice_kind`, `invoice_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
