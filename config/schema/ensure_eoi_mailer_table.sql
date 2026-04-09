-- EOI mailer log table (legacy `EoiMailer` model). Run once if missing.

CREATE TABLE IF NOT EXISTS `eoi_mailer` (
  `id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `user_id` INT UNSIGNED NULL,
  `inserindivdualname` VARCHAR(255) NULL,
  `insertcompanyname` VARCHAR(255) NULL,
  `insertbuilderemal` VARCHAR(255) NULL,
  `date` DATE NULL,
  `projectname` VARCHAR(255) NULL,
  `insertname` VARCHAR(255) NULL,
  `subject` VARCHAR(512) NULL,
  `sender_email` VARCHAR(255) NULL,
  `mobile` VARCHAR(255) NULL,
  `landlineno` VARCHAR(255) NULL,
  `client_requested` VARCHAR(255) NULL,
  `status` INT NULL,
  `created` DATETIME NULL,
  `modified` DATETIME NULL,
  PRIMARY KEY (`id`),
  KEY `idx_eoi_mailer_user` (`user_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
