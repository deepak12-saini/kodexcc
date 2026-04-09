-- Admin login uses table `users` with plain-text password (legacy app behaviour).
-- Default credentials after this seed:
--   Username: admin
--   Password: admin123
-- Change the password in the database (or via admin UI) before production.

CREATE TABLE IF NOT EXISTS `users` (
  `id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `username` VARCHAR(255) NOT NULL,
  `password` VARCHAR(255) NOT NULL,
  `name` VARCHAR(255) DEFAULT NULL,
  `email` VARCHAR(255) DEFAULT NULL,
  `created` DATETIME DEFAULT NULL,
  `modified` DATETIME DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `username` (`username`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Replace existing seed row if re-run
INSERT INTO `users` (`id`, `username`, `password`, `name`, `email`, `created`, `modified`)
VALUES (1, 'admin', 'admin123', 'Administrator', 'admin@localhost', NOW(), NOW())
ON DUPLICATE KEY UPDATE
  `password` = VALUES(`password`),
  `name` = VALUES(`name`),
  `email` = VALUES(`email`),
  `modified` = NOW();

CREATE TABLE IF NOT EXISTS `login_histories` (
  `id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `admin_id` INT UNSIGNED DEFAULT NULL,
  `user_id` INT UNSIGNED DEFAULT NULL,
  `role` VARCHAR(50) DEFAULT NULL,
  `logintime` DATETIME DEFAULT NULL,
  `logouttime` DATETIME DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `admin_id` (`admin_id`),
  KEY `user_id` (`user_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
