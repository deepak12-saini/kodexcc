-- Login tracking uses table `login_histories` (see LoginHistoryTable::setTable).
-- Run this once if the table does not exist (e.g. only init_schema.sql was imported).

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
