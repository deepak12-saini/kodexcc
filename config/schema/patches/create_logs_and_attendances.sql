-- Dialer / attendance tables used by Admin Sales (legacy `logs`, `attendances`).
-- Safe to run on DBs that already have these tables (CREATE IF NOT EXISTS).

CREATE TABLE IF NOT EXISTS `attendances` (
  `id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `staff_id` INT UNSIGNED NULL,
  `address` VARCHAR(512) NULL,
  `created` DATETIME NULL,
  PRIMARY KEY (`id`),
  KEY `idx_attendances_staff` (`staff_id`),
  KEY `idx_attendances_created` (`created`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS `logs` (
  `id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `user_id` INT UNSIGNED NULL,
  `client_id` INT UNSIGNED NULL,
  `phone_number` VARCHAR(64) NULL,
  `voice_url` VARCHAR(512) NULL,
  `call_duration` INT NULL,
  `created` DATETIME NULL,
  PRIMARY KEY (`id`),
  KEY `idx_logs_user` (`user_id`),
  KEY `idx_logs_client` (`client_id`),
  KEY `idx_logs_created` (`created`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
