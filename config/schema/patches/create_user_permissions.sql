-- Maps staff/customer to permission rows (legacy column name: permssion_id).
-- App uses table name `user_permissions` (see Admin\UsersController raw DELETE).

CREATE TABLE IF NOT EXISTS `user_permissions` (
  `id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `user_id` INT UNSIGNED NOT NULL,
  `permssion_id` INT UNSIGNED NOT NULL,
  `meta_val` VARCHAR(255) NULL,
  `created` DATETIME NULL,
  `modified` DATETIME NULL,
  PRIMARY KEY (`id`),
  KEY `idx_user_permissions_user` (`user_id`),
  KEY `idx_user_permissions_perm` (`permssion_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
