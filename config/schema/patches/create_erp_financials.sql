-- ERP financials: chart of accounts, journals, period lock. Safe to re-run.

CREATE TABLE IF NOT EXISTS `erp_accounts` (
  `id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `code` VARCHAR(20) NOT NULL,
  `name` VARCHAR(160) NOT NULL,
  `account_type` VARCHAR(20) NOT NULL,
  `status` VARCHAR(20) NOT NULL DEFAULT 'active',
  PRIMARY KEY (`id`),
  UNIQUE KEY `uk_erp_accounts_code` (`code`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT IGNORE INTO `erp_accounts` (`code`, `name`, `account_type`, `status`) VALUES
('1000', 'Bank', 'asset', 'active'),
('1100', 'Accounts receivable', 'asset', 'active'),
('1200', 'Inventory', 'asset', 'active'),
('1300', 'GST input', 'asset', 'active'),
('2000', 'Accounts payable', 'liability', 'active'),
('2100', 'GST output', 'liability', 'active'),
('3000', 'Capital', 'equity', 'active'),
('4000', 'Sales', 'income', 'active'),
('5000', 'Purchases', 'expense', 'active');

UPDATE `erp_bank_accounts` b
JOIN `erp_accounts` a ON a.code = '1000'
SET b.account_id = a.id
WHERE b.name = 'Main bank' AND (b.account_id IS NULL OR b.account_id = 0);

CREATE TABLE IF NOT EXISTS `erp_journals` (
  `id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `entry_no` VARCHAR(40) NOT NULL,
  `entry_date` DATE NOT NULL,
  `source_type` VARCHAR(40) NOT NULL,
  `source_id` INT UNSIGNED NULL,
  `narration` VARCHAR(255) NULL,
  `created` DATETIME NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `uk_erp_journal_no` (`entry_no`),
  KEY `idx_erp_journal_source` (`source_type`, `source_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS `erp_journal_lines` (
  `id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `journal_id` INT UNSIGNED NOT NULL,
  `account_id` INT UNSIGNED NOT NULL,
  `debit` DECIMAL(14,2) NOT NULL DEFAULT 0,
  `credit` DECIMAL(14,2) NOT NULL DEFAULT 0,
  PRIMARY KEY (`id`),
  KEY `idx_erp_jl_journal` (`journal_id`),
  KEY `idx_erp_jl_account` (`account_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS `erp_periods` (
  `id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `period` CHAR(7) NOT NULL,
  `locked` TINYINT(1) NOT NULL DEFAULT 1,
  `locked_on` DATETIME NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `uk_erp_period` (`period`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
